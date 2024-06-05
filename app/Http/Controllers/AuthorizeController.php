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
                $accountingRoleId = intval(env('ACCOUNTING_ROLE_ID', '9'));
                $accountingHeadRoleId = intval(env('ACCOUNTING_HEAD_ROLE_ID', '10'));
                if(in_array($roleId, [$accountingRoleId,$accountingHeadRoleId])){
                    Auth::loginUsingId($userId);

                    if(Auth::check()){
                        return redirect()->route('home');
                    }
                }
            }
        }
        
        return redirect()->route('access-denied');
    }
}
