<?php

namespace App\Http\Controllers\Web;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\ApplyNow;
use App\Models\Banner;
use App\Models\CarrerCounselling;
use App\Models\ContactInfo;
use App\Models\ContactUs;
use App\Models\CmsFaq;
use App\Models\CmsFaqCategory;
use App\Models\OurCourse;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
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
        $banner=Banner::first();
        $courses = OurCourse::where('status', 'Active')->get();
        $testimonial=Testimonial::where('status','Active')->orderBy('created_at', 'asc')->get();

        return view('frontend.pages.index',compact('testimonial','banner','courses'));
    }

    public function counselling(Request $request){

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

            $comments = new CarrerCounselling();

            $comments->name = $request->name;
            $comments->phone = $request->phone;
            $comments->email = $request->email;
            $comments->course_id  = $request->course_id;
            $comments->save();

            $course=OurCourse::where('id',$request->course_id)->first();
            $course_name=$course->name;

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
        return view('frontend.pages.maac', compact('courses', 'cmsCourses', 'cmsFeatures'));
    }

 public function aksha()
    {
        return view('frontend.pages.aksha');
    }

 public function faq(BrandContextManager $brandContextManager)
    {
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

        return view('frontend.pages.faq', compact('categories'));
    }

 public function space_e_fic()
    {
        return view('frontend.pages.space_e_fic');
    }

 public function fcq()
    {
        return view('frontend.pages.fcq');
    }

 public function showcase(CmsShowcaseReadService $showcaseReadService)
    {
        $showcaseCategories = $showcaseReadService->getCategoriesPublic();
        $showcaseProjects = $showcaseReadService->getProjectsPublic();
        return view('frontend.pages.showcase', compact('showcaseCategories', 'showcaseProjects'));
    }

 public function blog()
    {
        return view('frontend.pages.blog');
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

