<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Backend\Users;
use Helper, File, Session, Hash, Auth;

class UserController extends Controller
{    

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function loginForm()
    {        
        if(Auth::check()){            
            return redirect()->route('customer.index');
        } 
        return view('backend.login');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  Request  $request
    * @return Response
    */
    public function checkLogin(Request $request)
    {
        $dataArr = $request->all();
        
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required'
        ],
        [
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password'            
        ]);
        $dataArr = [
            'email' => $request->email,
            'password' => $request->password            
        ];
        if (Auth::validate($dataArr)) {
            $dataArr['status'] = 1;
            if (Auth::attempt($dataArr)) {  
                return redirect()->route('customer.index'); 
            }else{
                Session::flash('error', 'Account has been locked.'); 
                return redirect()->route('backend.login-form'); 
            }
        }else {
            // if any error send back with message.
            Session::flash('error', 'Email or password incorret.'); 
            return redirect()->route('backend.login-form');
        }
    }
  
    public function logout()
    {
        Auth::logout();
        return redirect()->route('backend.login-form');
    }   
}
