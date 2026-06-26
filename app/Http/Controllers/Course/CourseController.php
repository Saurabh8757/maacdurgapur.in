<?php

namespace App\Http\Controllers\Course;

use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\OurCourse;
use Illuminate\Http\Request;
use App\Models\CarrerCounselling;
use Illuminate\Support\Facades\File;

class CourseController extends Controller
{
    public function index()
    {
        $allCourses = OurCourse::orderBy('created_at','DESC')->get();

        return view('admin.pages.courses.index',['allCourses' => $allCourses]);
    }

    public function add()
    {
        return view('admin.pages.courses.add');

    }

    public function save(Request $request)
    {
        //dd($request->all());
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Phone Code',

        ];
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'name' => 'required',
            'duration' => 'required',

        ],$msg);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = "upload/images/course";
            $filename = $file->hashName(); // Secure random filename
            if (!is_dir(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            // Move the uploaded file to the specified path
            $file->move(public_path($path), $filename);

            $image_name = $path . '/' . $filename;
            $this->mirrorCourseImageToDocumentRoot($image_name);
        } else {
            $image_name = '';
        }

        $save = new OurCourse();
        $save->name = $request->get('name');
        $save->desc = $request->get('duration');
        $save->image = $image_name;

        $save->save();


        return redirect()->route('admin::course')->with('success','Course Added Successfully');

    }

    public function edit($id)
    {
        $data = OurCourse::where('id',$id)->first();
        return view('admin.pages.courses.edit',['data' => $data]);
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
            $request->validate(['image' => 'image|mimes:jpg,jpeg,png,webp|max:10240']);
            $file = $request->file('image');
            $path = "upload/images/course";
            $filename = $file->hashName(); // Secure random filename
            if (!is_dir(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            // Remove the old image file if it exists
            $this->deleteCourseImageFiles($old);

            // Move the new uploaded file to the specified path
            $file->move(public_path($path), $filename);
            $image_name = $path . '/' . $filename;
            $this->mirrorCourseImageToDocumentRoot($image_name);
        } else {
            $image_name = $old;
        }

        $data->name = $request->get('name');
        $data->desc = $request->get('duration');
        $data->image = $image_name;
        $data->save();


        return redirect()->route('admin::course')->with('success','Course Updated Successfully');

    }

    public function delete($id)
    {
        $data = OurCourse::where('id',$id)->first();
        if (!empty($data)) {
            $oldImage = $data->image;
            $this->deleteCourseImageFiles($oldImage);
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
                'status' => 'InActive',
            ]);
            $st='InActive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.json_encode($st).')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            OurCourse::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.json_encode($st).')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

    private function mirrorCourseImageToDocumentRoot(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $source = public_path($relativePath);
        $documentRoot = rtrim((string) request()->server('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR);

        if ($documentRoot === '' || !file_exists($source)) {
            return;
        }

        $target = $documentRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

        if (realpath(dirname($source)) === realpath(dirname($target))) {
            return;
        }

        File::ensureDirectoryExists(dirname($target), 0755, true);
        File::copy($source, $target);
    }

    private function deleteCourseImageFiles(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $paths = [public_path($relativePath)];
        $documentRoot = rtrim((string) request()->server('DOCUMENT_ROOT'), DIRECTORY_SEPARATOR);

        if ($documentRoot !== '') {
            $paths[] = $documentRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        }

        foreach (array_unique($paths) as $path) {
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }
    
}
