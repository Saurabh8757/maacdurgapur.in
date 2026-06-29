<?php

namespace App\Http\Controllers\Web;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\ApplyNow;
use App\Models\CarrerCounselling;
use App\Models\ContactUs;
use App\Models\CmsFaq;
use App\Models\CmsFaqCategory;
use App\Models\OurCourse;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsCourseReadService;
use App\Services\Cms\CmsFeatureReadService;
use App\Services\Cms\CmsShowcaseReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;

class PageController extends Controller
{
    public function index()
    {
        $courses = OurCourse::where('status', 'Active')->get();

        $brand = \App\Models\Brand::where('slug', 'maac')->first();
        
        $placements = \App\Models\PlacementShowcase::with(['company', 'studentImage'])
            ->where('is_active', true)
            ->where(function($q) use ($brand) {
                if ($brand) {
                    $q->where('brand_id', $brand->id)->orWhereNull('brand_id');
                }
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $recruiters = \Illuminate\Support\Facades\Cache::remember('homepage_recruiters', 3600, function() use ($brand) {
            return \App\Models\Recruiter::with('logo')
                ->where('is_active', true)
                ->where(function($q) use ($brand) {
                    if ($brand) {
                        $q->where('brand_id', $brand->id)->orWhereNull('brand_id');
                    }
                })
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        $formFields = [];
        $globalModalFormFields = [];
        if ($brand) {
            $formFields = \App\Models\LeadFormField::where('brand_id', $brand->id)
                ->where('form_type', 'hero')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
            $globalModalFormFields = \App\Models\LeadFormField::where('brand_id', $brand->id)
                ->where('form_type', 'global_modal')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        return view('frontend.pages.index',compact('courses', 'brand', 'formFields', 'globalModalFormFields', 'placements', 'recruiters'));
    }

    public function counselling(Request $request){

        // Feature Flag Check dynamically based on brand
        if ($request->has('brand_id')) {
            $brand = \App\Models\Brand::find($request->brand_id);
            if ($brand) {
                $brand_id = $brand->id;
            
            // Fetch active fields for this brand and form type
            $formType = $request->input('form_type', 'hero');
            $fields = collect();
            if (true) {
                $fields = \App\Models\LeadFormField::where('brand_id', $brand_id)
                    ->where('form_type', $formType)
                    ->where('is_active', true)
                    ->get();
            }
                
            $rules = [];
            $messages = [];

            if ($fields->isEmpty()) {
                $rules = [
                    'name' => 'required',
                    'phone' => 'required|numeric',
                    'email' => 'required|email',
                    'course_id' => 'required',
                ];
                $messages = [
                    'name.required' => 'Please enter your Full Name',
                    'phone.required' => 'Please enter phone number',
                    'phone.numeric' => 'phone number must be a numeric format',
                    'email.required' => 'Please enter your Email',
                    'email.email' => ' Email should be a valid format',
                    'course_id.required' => 'Please Select Course',
                ];
            }
            
            foreach ($fields as $field) {
                if ($field->is_required) {
                    $rules[$field->field_name] = 'required';
                    $messages[$field->field_name . '.required'] = 'Please enter ' . $field->label;
                } else {
                    $rules[$field->field_name] = 'nullable';
                }
                
                if ($field->type === 'email') {
                    $rules[$field->field_name] .= '|email';
                    $messages[$field->field_name . '.email'] = $field->label . ' should be a valid format';
                }
                if ($field->type === 'phone') {
                    $rules[$field->field_name] .= '|numeric';
                    $messages[$field->field_name . '.numeric'] = $field->label . ' must be a numeric format';
                }
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            // Determine Course Name
            $course_name = null;
            if ($request->has('course_id')) {
                // If it's a numeric ID (like MAAC legacy), get name. Otherwise, store raw string (like SpaceEFic/Aksha).
                if (is_numeric($request->course_id)) {
                    $course = OurCourse::find($request->course_id);
                    $course_name = $course ? $course->name : $request->course_id;
                } else {
                    $course_name = $request->course_id;
                }
            }

            // Separate core fields from custom data
            $coreFields = ['name', 'phone', 'email', 'location', 'message'];
            $leadData = [
                'brand_id' => $brand_id,
                'course_name' => $course_name,
                'status' => 'new'
            ];
            
            if ($formType === 'global_modal') {
                $leadData['source_page'] = 'Global Modal';
            }
            // Always save core fields if present in request
            foreach ($coreFields as $coreField) {
                if ($request->has($coreField) && !empty($request->$coreField)) {
                    $leadData[$coreField] = $request->$coreField;
                }
            }

            $customData = [];
            foreach ($fields as $field) {
                $fname = $field->field_name;
                if ($fname === 'course_id' || in_array($fname, $coreFields)) continue;

                if ($request->has($fname)) {
                    $customData[$fname] = $request->$fname;
                }
            }

            if (!empty($customData)) {
                $leadData['custom_data'] = $customData;
            }

            $newLead = \App\Models\Lead::create($leadData);
            
            $activityService = app(\App\Services\LeadActivityService::class);
            $activityService->logLeadCreated($newLead);

            // Trigger Notification
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendToBrand(
                $newLead->brand_id,
                'New Lead Submitted',
                "A new lead {$newLead->name} submitted the form.",
                'lead_created',
                'Lead',
                $newLead->id,
                route('admin::leads.show', $newLead->id)
            );

            // Trigger WhatsApp Acknowledgement
            try {
                $whatsappService = app(\App\Services\WhatsApp\WhatsappServiceInterface::class);
                $whatsappService->sendLeadAcknowledgement($newLead);
            } catch (\Throwable $exception) {
                \Illuminate\Support\Facades\Log::warning('Lead WhatsApp acknowledgement failed.', [
                    'lead_id' => $newLead->id,
                    'exception' => $exception->getMessage(),
                ]);
            }

            return response()->json([
                'status' => 1,
                'success' => 'Successfully sent your request'
            ]);
            }
        }
        // --- LEGACY FALLBACK ---
        $msg = [
            'name.required' => 'Please enter your Full Name',
            'email.required' => 'Please enter your Email',
            'email.email' => ' Email should be a valid format',
            'phone.required' => 'Please enter phone number',
            'phone.numeric' => 'phone number must be a numeric format',
            'course_id.required' => 'Please Select Course',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email',
                'course_id' => 'required',
            ],
            $msg
        );
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            // Save to Legacy Table (for safety and external scripts)
            $comments = new CarrerCounselling();
            $comments->name = $request->name;
            $comments->phone = $request->phone;
            $comments->email = $request->email;
            $comments->course_id  = $request->course_id;
            $comments->save();

            // Save to Unified Lead Management (so it shows in CMS)
            $brand = \App\Models\Brand::where('slug', 'maac')->first();
            if ($brand) {
                $courseName = null;
                if ($request->course_id) {
                    $course = \App\Models\OurCourse::find($request->course_id);
                    if ($course) {
                        $courseName = $course->name;
                    }
                }
                $newLead = \App\Models\Lead::create([
                    'brand_id' => $brand->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'course_name' => $courseName,
                    'source_page' => 'Global Modal',
                    'status' => 'new'
                ]);

                $activityService = app(\App\Services\LeadActivityService::class);
                $activityService->logLeadCreated($newLead);

                // Trigger Notification
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendToBrand(
                    null, // Superadmin
                    'New Lead Submitted',
                    'A new lead ('.$newLead->name.') has been submitted for '.$newLead->course_name,
                    'success',
                    'Lead',
                    $newLead->id,
                    route('admin::leads.show', $newLead->id),
                    'fas fa-user-plus',
                    'success'
                );

                // Trigger WhatsApp Acknowledgement
                try {
                    $whatsappService = app(\App\Services\WhatsApp\WhatsappServiceInterface::class);
                    $whatsappService->sendLeadAcknowledgement($newLead);
                } catch (\Throwable $exception) {
                    \Illuminate\Support\Facades\Log::warning('Lead WhatsApp acknowledgement failed.', [
                        'lead_id' => $newLead->id,
                        'exception' => $exception->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'status' => 1,
                'success' => 'Successfully sent your request'
            ]);
        }
    }

public function terms()
    {
        

        return view('frontend.pages.t_and_c');
    }

 public function maac(CmsCourseReadService $cmsCourseReadService, CmsFeatureReadService $cmsFeatureReadService)
    {
        $courses = OurCourse::where('status', 'Active')->get();
        $cmsCourses = $cmsCourseReadService->getAllPublic();
        $cmsFeatures = $cmsFeatureReadService->getAllPublic();
        
        $brand = \App\Models\Brand::where('slug', 'maac')->first();
        $formFields = [];
        if ($brand) {
            $formFields = \App\Models\LeadFormField::where('brand_id', $brand->id)
                ->where('form_type', 'hero')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        return view('frontend.pages.maac', compact('courses', 'cmsCourses', 'cmsFeatures', 'brand', 'formFields'));
    }

 public function aksha()
    {
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();
        $brand = \App\Models\Brand::where('slug', 'aksha')->first();
        $formFields = [];
        if ($brand) {
            $formFields = \App\Models\LeadFormField::where('brand_id', $brand->id)
                ->where('form_type', 'hero')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        $majorPrograms = \App\Models\AkshaMajorProgram::with('featuredImage')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $supportingCourses = \App\Models\AkshaSupportingCourse::with('featuredImage')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.pages.aksha', compact('courses', 'brand', 'formFields', 'majorPrograms', 'supportingCourses'));
    }

 public function faq(BrandContextManager $brandContextManager)
    {
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();
        $brandId = $brandContextManager
            ->requirePublicContext()
            ->brand()
            ->getKey();

        $categories = CmsFaqCategory::with(['faqs' => function ($query) use ($brandId) {
                $query
                    ->where('brand_id', $brandId)
                    ->where('status', 'active')
                    ->orderBy('sort_order')
                    ->orderBy('id');
            }])
            ->where('brand_id', $brandId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('frontend.pages.faq', compact('categories', 'courses'));
    }

 public function space_e_fic()
    {
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();
        $brand = \App\Models\Brand::where('slug', 'space-e-fic')->first();
        $formFields = [];
        if ($brand) {
            $formFields = \App\Models\LeadFormField::where('brand_id', $brand->id)
                ->where('form_type', 'hero')
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        $spaceEFicCourses = \App\Models\SpaceEFicCourse::where('is_active', true)->orderBy('order')->get();

        return view('frontend.pages.space_e_fic', compact('courses', 'brand', 'formFields', 'spaceEFicCourses'));
    }

 public function fcq()
    {
        return view('frontend.pages.fcq');
    }

 public function showcase(CmsShowcaseReadService $showcaseReadService)
    {
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();
        $showcaseCategories = $showcaseReadService->getCategoriesPublic();
        $showcaseProjects = $showcaseReadService->getProjectsPublic();
        return view('frontend.pages.showcase', compact('showcaseCategories', 'showcaseProjects', 'courses'));
    }

 public function blog()
    {
        $courses = \App\Models\OurCourse::where('status', 'Active')->get();
        return view('frontend.pages.blog', compact('courses'));
    }
    
 public function web()
    {
         $courses = OurCourse::where('status', 'Active')->get();

        return view('frontend.pages.web',compact('courses'));
    }

 public function motion()
    {
         $courses = OurCourse::where('status', 'Active')->get();

        return view('frontend.pages.motion',compact('courses'));
    } 
 
}

