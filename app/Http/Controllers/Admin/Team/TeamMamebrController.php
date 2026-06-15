<?php

namespace App\Http\Controllers\Admin\Team;

use App\Helper\admin\GetTeamProfileLink;
use App\Helper\admin\ImageUpload;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMamebrController extends Controller
{
    public function index()
    {
        $allMembers = TeamMember::orderBy('created_at','DESC')->get();
        return view('admin.pages.team_member.index',['allMembers' => $allMembers]);
    }

    public function add()
    {
        return view('admin.pages.team_member.add');

    }

    public function save(Request $request)
    {
        $msg = [
            'image.required' => 'Please Select A Image',
            'name.required' => 'Please Enter Phone Code',
            'occupation.required' => 'Please Enter Occupation',
            'description.required' => 'Please Enter Website',
        ];
        $this->validate($request, [
            'image' => 'required',
            'name' => 'required',
            'occupation' => 'required',
            'description' => 'required',
        ],$msg);

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/team/";
            $filename = ImageUpload::saveImage($path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = '';
        }

        $save = new TeamMember();
        $save->name = $request->get('name');
        $save->profile_photo = $image_name;
        $save->occupation = $request->get('occupation');
        $save->facebook = $request->get('facebook');
        $save->twitter = $request->get('twitter');
        $save->linkedin = $request->get('linkedin');
        $save->youtube = $request->get('youtube');
        $save->description = $request->get('description');
        $save->save();

        return redirect()->route('admin::team_member')->with('success','Team Member Add Successfully');

    }

    public function edit($id)
    {
        $data = TeamMember::where('id',$id)->first();
        return view('admin.pages.team_member.edit',['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $msg = [
            'name.required' => 'Please Enter Phone Code',
            'occupation.required' => 'Please Enter Phone Number',
            'description.required' => 'Please Enter Website',
        ];
        $this->validate($request, [
            'name' => 'required',
            'occupation' => 'required',
            'description' => 'required',
        ],$msg);

        $data = TeamMember::where('id',$id)->first();
        $old = $data->profile_photo;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $path = "upload/images/team/";
            $filename = ImageUpload::updateImage($old, $path, $file);
            $image_name = $path.$filename;
        }else{
            $image_name = $old;
        }

        $link = GetTeamProfileLink::retunData($request->get('youtube'),'yt');
        $facebook = GetTeamProfileLink::retunData($request->get('facebook'));
        $twitter = GetTeamProfileLink::retunData($request->get('twitter'));
        $linkedin = GetTeamProfileLink::retunData($request->get('linkedin'));

        $data->name = $request->get('name');
        $data->profile_photo = $image_name;
        $data->occupation = $request->get('occupation');
        $data->facebook = $facebook;
        $data->twitter = $twitter;
        $data->linkedin = $linkedin;
        $data->youtube = $link;
        $data->description = $request->get('description');
        $data->save();

        return redirect()->route('admin::team_member')->with('success','Team Member Update Successfully');

    }

    public function delete($id)
    {
        $data = TeamMember::where('id',$id)->first();
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
            TeamMember::where('id',$id)->update([
                'status' => 'Inactive',
            ]);
            $st='Inactive';
            $html='<a href="javascript:void(0);" class="btn btn-warning btn-sm" onclick="active_inactive_banner('.$id.','.$st.')" ><span class="fa fa-ban"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
        else{
            TeamMember::where('id',$id)->update([
                'status' => 'Active',
            ]);
            $st='Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="active_inactive_banner('.$id.','.$st.')"><span class="fa fa-check"></span> </a>&emsp;';
            return json_encode(array('id'=>$id,'html'=>$html, 'status'=>$st));
        }
    }

}
