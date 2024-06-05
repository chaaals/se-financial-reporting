<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthorizeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $env = env('APP_ENV', 'local');
      	
        if($env == 'production'){
            $userId = Session::get('user_id');
            $password = Session::get('password');
            $roleId = Session::get('role_id');
          	
            if($userId && $password){
                if(in_array($roleId, [9,10])){
                    Auth::loginUsingId($userId);

                    if(Auth::check()){
                        return redirect()->route('/');
                    }
                }
            }
        }
        
        return redirect()->route('access-denied');
    }
}
