<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\SubscriptionSocietyPackages;
use Session;
use App\RolePermission;
use App\EmailIntegrations;
use App\Society;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function checkLogin(Request $request){
        $request->validate([
            'email' => 'required|email',            
            'password' => 'required'            
        ]);

        $checkEmail = User::where("s_user_email",$request->email)->first();
        if(!empty($checkEmail)){
            $password = md5($request->input("password"));
            if($checkEmail->password_md5 == $password){
                $society = Society::where('id', $checkEmail->society_id)->first();
                if($society->status == 1)
                {
                    $end_date = SubscriptionSocietyPackages::where('society_id', $checkEmail->society_id)->first();
                    $permission = RolePermission::where('society_id', $checkEmail->society_id)->where('role_id', $checkEmail->role)->first();
                    // echo '<pre>'; print_r($end_date); exit;
                    if(!empty($permission))
                    {
                        $today = date('Y-m-d');
                        if(!empty($end_date))
                        {
                            if(strtotime($end_date->end_date) < strtotime($today))
                            {
                                // echo strtotime($end_date->end_date).' <br> '.strtotime($today); exit; 
                                return redirect()->route('expired');
                            }
                            else{
                                $emailServices = EmailIntegrations::where('status', 1)->where('society_id', $checkEmail->society_id)->first();
                                $society = Society::where('id', $checkEmail->society_id)->first();
                                
                                if(!empty($emailServices))
                                {
                                    envUpdate('MAIL_HOST', $emailServices->smtp_host);
                                    envUpdate('MAIL_PORT', $emailServices->smtp_port);
                                    envUpdate('MAIL_USERNAME', $emailServices->smtp_user_name);
                                    envUpdate('MAIL_PASSWORD', $emailServices->smtp_password);
                                    envUpdate('MAIL_FROM_ADDRESS', $emailServices->from_email);
                                    // envUpdate('MAIL_FROM_NAME', "");
                                    // envUpdate('MAIL_FROM_NAME', 'abc');
                                }
                                \Auth::loginUsingId($checkEmail->id);
                                Session::put('package-msg', '1');
                                return redirect("/");
                            }
                        }
                        else{
                            // echo 'ok'; exit;
                            return redirect()->route('expired');
                        }
                    }
                    else{
                        return redirect()->route('permission');
                    }
                }
                else
                {
                    return redirect()->back()->with("error","Login Error Or Bed Credentials");
                }
            }else{
                return redirect()->back()->with("error","Invalid Email or Password!");
            }
        }else{
            return redirect()->back()->with("error","Invalid Email or Password!");
        }
    }
}
