<?php

namespace App\Http\Controllers\Admin\About;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index()
    {
        $allAbout = AboutPage::get();

        return view('admin.pages.about_page.index',['allAbout'=>$allAbout]);
    }

    public function edit($id)
    {
        $data = AboutPage::where('id',$id)->first();
        return view('admin.pages.about_page.edit',['data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $msg = [
            'title.required' => 'Plesae Enter Title.',
            'description.required' => 'Plesae Enter Description.',
        ];
        $this->validate($request, [
//            'image' => 'mimes:jpg,jpeg,png,webp',
            'title' => 'required',
            'description' => 'required',
        ], $msg);

        $data = AboutPage::where('id',$id)->first();
//        $old = $data->image;
//        if ($request->hasFile('image')){
//            $file = $request->file('image');
//            $path = "upload/images/about/";
//            $filename = ImageUpload::updateImage($old, $path, $file);
//            $image_name = $path.$filename;
//        }else{
//            $image_name = $old;
//        }
        $data->image = $request->image;
        $data->title = $request->get('title');
        $data->description = $request->get('description');
        $data->save();

        return redirect()->route('admin::about')->with('success','About Update Successfully');

    }

    public function status(Request $request){
        $id = $request->get('id');
        $status = $request->get('status');
        if($status=='Active'){
            AboutPage::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_about('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            AboutPage::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_about('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

}
