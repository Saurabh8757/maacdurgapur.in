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
use App\Models\OurCourse;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
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
        // dd($courses);
        $testimonial=Testimonial::where('status','Active')->orderBy('created_at', 'asc')->get();
        //dd($testimonial);

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

            // Mail::to('mondalsoumen071@gmail.com')->send(new AdminNotification($request->name, $request->phone, $request->email, $course_name));

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

 public function maac()
    {
        $courses = OurCourse::where('status', 'Active')->get();
        return view('frontend.pages.maac', compact('courses'));
    }

 public function aksha()
    {
        return view('frontend.pages.aksha');
    }

 public function space_e_fic()
    {
        return view('frontend.pages.space_e_fic');
    }

 public function fcq()
    {
        return view('frontend.pages.fcq');
    }

 public function showcase()
    {
        return view('frontend.pages.showcase');
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


