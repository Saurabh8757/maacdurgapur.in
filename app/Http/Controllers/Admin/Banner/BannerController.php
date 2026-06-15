<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $allBanner = Banner::get();
        return view('admin.pages.banner.index',['allBanner'=>$allBanner]);
    }

    public function add()
    {
        return view('admin.pages.banner.add');
    }

    public function save(Request $request)
    {
        $msg = [
            'image.required' => 'Plesae Choose An Image.',
        ];
        $this->validate($request, [
            'image' => 'required|mimes:jpg,jpeg,png,webp',
        ], $msg);

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/banner/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }
        $save = new Banner();
        $save->image = $image_name;
        $save->title = $request->get('title');
        $save->description = $request->get('description');
        $save->save();

        return redirect()->route('admin::banner')->with('success','Banner Add Successfully');
    }

    public function edit($id)
    {
        $data = Banner::where('id',$id)->first();
        return view('admin.pages.banner.edit',['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'mimes:jpg,jpeg,png,webp',
        ]);

        $data = Banner::where('id',$id)->first();
        $old = $data->image;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/banner/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }
        $data->image = $image_name;
        $data->title = $request->get('title');
        $data->description = $request->get('description');
        $data->save();

        return redirect()->route('admin::banner')->with('success','Banner Update Successfully');
    }

    public function delete($id){
        $data = Banner::where('id',$id)->first();
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

    public function status(Request $request){
        $id = $request->get('id');
        $status = $request->get('status');
        if($status=='Active'){
            Banner::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            Banner::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }
}
