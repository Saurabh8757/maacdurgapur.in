<?php

namespace App\Http\Controllers\Admin\Service;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $allServices = Service::orderBy('created_at','DESC')->get();
        return view('admin.pages.service.index',['allServices' => $allServices]);
    }

    public function add()
    {
        return view('admin.pages.service.add');
    }

    public function save(Request $request)
    {
        $msg = [
            'image.required' => 'Please Select A Image',
            'title.required' => 'Please Enter Title',
            'description.required' => 'Please Enter Description',
        ];
        $this->validate($request, [
            'image' => 'required',
            'title' => 'required',
            'description' => 'required',
        ],$msg);

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/services/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }

        $serive = new  Service();
        $serive->title = $request->get('title');
        $serive->image = $image_name;
        $serive->description = $request->get('description');
        $serive->service_type = 'our';
        $serive->save();

        return redirect()->back()->with('success','Service Add Successfully');
    }


    public function edit($id)
    {
        $data = Service::where('id',$id)->first();
        return view('admin.pages.service.edit',['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $msg = [
            'image.mimes' => 'Please Select A Valid Image',
            'title.required' => 'Please Enter Title',
            'description.required' => 'Please Enter Description',
        ];
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png,webp',
            'title' => 'required',
            'description' => 'required',
        ],$msg);

        $serive = Service::where('id',$id)->first();

        $old = $serive->image;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/services/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }

        $serive->title = $request->get('title');
        $serive->image = $image_name;
        $serive->description = $request->get('description');
        $serive->service_type = 'our';
        $serive->save();

        return redirect()->back()->with('success','Service Update Successfully');
    }

    public function delete($id)
    {
        $data = Service::where('id',$id)->first();
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
            Service::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            Service::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

}
