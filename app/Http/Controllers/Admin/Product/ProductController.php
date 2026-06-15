<?php

namespace App\Http\Controllers\Admin\Product;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products=Product::all();
        return view('admin.pages.product.index',compact('products'));
    }
    public function add(){
        return view('admin.pages.product.add');
    }

    public function save(Request $request)
    {
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Product Name',
            'name.unique' => 'Product Name must be unique',
            'desc.required' => 'Please Enter Description',
        ];
        $this->validate($request, [
            'image' => 'required',
            'name' => 'required|unique:products,name',
            'desc' => 'required',
        ],$msg);

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/product/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }
        $slug_title = str_replace(' ', '', strtolower($request['name']));
        $serive = new  Product();
        $serive->name = $request->get('name');
        $serive->product_slug = $slug_title;
        $serive->image = $image_name;
        $serive->desc = $request->get('desc');
        $serive->save();

        return redirect()->back()->with('success','Product Add Successfully');
    }
    public function edit($id){

        $data = Product::where('id',$id)->first();
        return view('admin.pages.product.edit',['data' => $data]);

    }


    public function update(Request $request, $id)
    {
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Product Name',
            'name.unique' => 'Product Name must be unique',
            'desc.required' => 'Please Enter Description',
        ];
        $this->validate($request, [
            'image' => 'mimes:jpg,jpeg,png,webp',
            'name' => 'required|unique:products,name,'.$id,
            'desc' => 'required',
        ],$msg);


        $slug_title = str_replace(' ', '', strtolower($request['name']));
        $data = Product::where('id',$id)->first();
        $old = $data->image;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/product/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }
        $data->image = $image_name;
        $data->name = $request->get('name');
        $data->product_slug = $slug_title;
        $data->desc = $request->get('desc');
        $data->save();

        return redirect()->back()->with('success','Product Update Successfully');
    }

    public function delete($id){
        $data = Product::where('id',$id)->first();
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
            Product::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            Product::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

}
