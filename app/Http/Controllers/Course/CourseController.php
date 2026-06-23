<?php

namespace App\Http\Controllers\Course;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\OurCourse;
use Illuminate\Http\Request;
use App\Models\CarrerCounselling;

class CourseController extends Controller
{
    public function index()
    {
        $allCourses = OurCourse::orderBy('created_at','DESC')->get();

        return view('admin.pages.team_member.index',['allCourses' => $allCourses]);
    }

    public function add()
    {
        return view('admin.pages.team_member.add');

    }

    public function save(Request $request)
    {
        //dd($request->all());
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Phone Code',

        ];
        $this->validate($request, [
            'image' => 'required',
            'name' => 'required',
            'duration' => 'required',

        ],$msg);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = "upload/images/course";
            $filename = $file->getClientOriginalName(); // Get the original file name

            // Move the uploaded file to the specified path
            $file->move($path, $filename);

            $image_name = $path . '/' . $filename;
        } else {
            $image_name = '';
        }

        $save = new OurCourse();
        $save->name = $request->get('name');
        $save->desc = $request->get('duration');
        $save->image = $image_name;

        $save->save();


        return redirect()->route('admin::course')->with('success','Team Member Add Successfully');

    }

    public function edit($id)
    {
        $data = OurCourse::where('id',$id)->first();
        return view('admin.pages.team_member.edit',['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $msg = [
            'name.required' => 'Please Enter course name',
            'duration.required' => 'Please Enter course duration',

        ];
        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',

        ],$msg);

        $data = OurCourse::where('id', $id)->first();
        $old = $data->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = "upload/images/course/";
            $filename = $file->getClientOriginalName();

            // Remove the old image file if it exists
            if (!empty($old) && file_exists(public_path($old))) {
                unlink(public_path($old));
            }

            // Move the new uploaded file to the specified path
            $file->move($path, $filename);
            $image_name = $path . $filename;
        } else {
            $image_name = $old;
        }

        $data->name = $request->get('name');
        $data->desc = $request->get('duration');
        $data->image = $image_name;
        $data->save();


        return redirect()->route('admin::course')->with('success','Team Member Update Successfully');

    }

    public function delete($id)
    {
        $data = OurCourse::where('id',$id)->first();
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
            OurCourse::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            OurCourse::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }
    
}
