<?php

namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    /*** Admin Login ***/
    // public function admin_login()
    // {
    //     return view('admin.login.login');
    // }
    
    
     public function admin_login_page()
    {
        return view('admin.login.login');
    }
    /*** Admin Login Check ***/
    public function admin_login_check(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors()
            ]);
        } else {

            $email = $request->input('email');
            $password = $request->input('password');


            if (Auth::attempt(['email'=>$email,'password'=>$password,'user_type'=>'Admin'])) {
                
                return response()->json(['status' => 200, 'message' => 'Login Submit Successfully']);
            } else {
                return response()->json(['status' => 201,'message' => 'Invalid Email Or Password']);
            }
        }
    }
}
