<?php

namespace App\Http\Controllers\Admin\Career;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\ApplyNow;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $allcareer = Career::where('status','Active')->get();
        return view('admin.pages.career.open_career.index',['allcareer' => $allcareer]);
    }

    public function add()
    {
        return view('admin.pages.career.open_career.add');
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
            $path = "upload/images/career/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }

        $serive = new  Career();
        $serive->title = $request->get('title');
        $serive->image = $image_name;
        $serive->description = $request->get('description');
        //$serive->service_type = 'our';
        $serive->save();

        return redirect()->back()->with('success','Service Add Successfully');
    }


    public function edit($id)
    {
        $data = Career::where('id',$id)->first();
        return view('admin.pages.career.open_career.edit',['data' => $data]);
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

        $serive = Career::where('id',$id)->first();

        $old = $serive->image;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/career/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }

        $serive->title = $request->get('title');
        $serive->image = $image_name;
        $serive->description = $request->get('description');
//        $serive->service_type = 'our';
        $serive->save();

        return redirect()->back()->with('success','Service Update Successfully');
    }

    public function delete($id)
    {
        $data = Career::where('id',$id)->first();
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
            Career::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            Career::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

    public function apply(){
        $applycareer = ApplyNow::all();
        return view('admin.pages.career.apply_career.index',['applycareer' => $applycareer]);
    }

    public function show($id)
    {
        $datas = ApplyNow::where('id',$id)->first();
        return view('admin.pages.career.apply_career.edit',['datas' => $datas]);
    }

    public function apply_delete($id)
    {
        $data = ApplyNow::where('id',$id)->first();
        if (!empty($data)) {
            $oldImage1 = $data->degree_certificate;
            $oldImage2 = $data->file;
            if (!empty($oldImage1)) {
                @unlink(public_path() . '/' . $oldImage1);
            }
            if (!empty($oldImage2)) {
                @unlink(public_path() . '/' . $oldImage2);
            }
            $data->delete();
            return response()->json(['status' => 200]);
        }else{
            return response()->json(['status' => 400]);
        }
    }

}
