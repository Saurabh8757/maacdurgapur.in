<?php

namespace App\Http\Controllers\Admin\Testimonial;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at','DESC')->get();
        return view('admin.pages.testimonial.index',['testimonials' => $testimonials]);
    }

    public function add()
    {
        return view('admin.pages.testimonial.add');
    }

    public function save(Request $request)
    {
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Phone Code',
            'occupation.required' => 'Please Enter Occupation',
            'message.required' => 'Please Enter Website',
        ];
        $this->validate($request, [
            'image' => 'required',
            'name' => 'required',
            'occupation' => 'required',
            'message' => 'required',
        ],$msg);

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/testimonials/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }

        $testimonial = new Testimonial();
        $testimonial->name = $request->get('name');
        $testimonial->profile_photo = $image_name;
        $testimonial->occupation = $request->get('occupation');
        $testimonial->message = $request->get('message');
        $testimonial->save();

        return redirect()->route('admin::testimonials')->with('success','Testimonial Add Successfully');
    }

    public function edit($id)
    {
        $data = Testimonial::where('id',$id)->first();
        return view('admin.pages.testimonial.edit',['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $msg = [
            'name.required' => 'Please Enter Phone Code',
            'occupation.required' => 'Please Enter Occupation',
            'message.required' => 'Please Enter Website',
        ];
        $this->validate($request, [
            'name' => 'required',
            'occupation' => 'required',
            'message' => 'required',
        ],$msg);


        $data = Testimonial::where('id',$id)->first();
        $old = $data->profile_photo;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/testimonials/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }
        $data->name = $request->get('name');
        $data->profile_photo = $image_name;
        $data->occupation = $request->get('occupation');
        $data->message = $request->get('message');
        $data->save();

        return redirect()->route('admin::testimonials')->with('success','Testimonial Update Successfully');

    }


    public function delete($id)
    {
        $data = Testimonial::where('id',$id)->first();
        if (!empty($data)) {
            $oldImage = $data->image;
            if (!empty($oldImage)) {
                @unlink(public_path() . '/' . $oldImage);
            }
            $data->delete();
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }

    public function status(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        if($status=='Active'){
            Testimonial::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            Testimonial::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

}
