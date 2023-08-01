<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailIntegrations;
use App\Society;
use Auth;
class MailIntegrationsController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function index()
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(68, $permissions))
            {
                $data = array();
                $data['page_title'] = "Mail Integrations"; 
                $data['sub_title'] = "Mail Integrations List";
                $mailintegrations = EmailIntegrations::where('society_id', Auth::user()->society_id)->get();
                $data['mailintegrations'] = $mailintegrations;
                
                $emailServices = EmailIntegrations::where('status', 1)->where('society_id', Auth::user()->society_id)->first();
                if(!empty($emailServices))
                {
                    envUpdate('MAIL_HOST', $emailServices->smtp_host);
                    envUpdate('MAIL_PORT', $emailServices->smtp_port);
                    envUpdate('MAIL_USERNAME', $emailServices->smtp_user_name);
                    envUpdate('MAIL_PASSWORD', $emailServices->smtp_password);
                    envUpdate('MAIL_FROM_ADDRESS', $emailServices->from_email);
                }
                
                return view('mail-integrations/index',$data);
            }
            else
            {
                return redirect()->back();
            }
        }else{
            return redirect('/login');
        }
    }

    public function create()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(69, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Mail Integrations"; 
            return view('mail-integrations.create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'smtp_user_name' => 'required',
            'smtp_password' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'use_smtp_simple' => 'required',
            'from_email' => 'required',
            'replay_email' => 'required',
        ]);
        
        $mail_count = EmailIntegrations::where('society_id', Auth::user()->society_id)->count();

        if($mail_count == 5)
        {
            return redirect()->back()->withInput()->with('error', 'not add more than 5 emails setting');
        }

        $active_count = EmailIntegrations::where('society_id', Auth::user()->society_id)->where('status', 1)->count();
        $status = 0;

        if($active_count == 0)
        {
            $status = 1;
        }

        $mail = new EmailIntegrations();
        $mail->society_id = Auth::user()->society_id;
        $mail->smtp_user_name = $request->smtp_user_name;
        $mail->smtp_password = $request->smtp_password;
        $mail->smtp_host = $request->smtp_host;
        $mail->smtp_port = $request->smtp_port;
        $mail->use_smtp_simple = $request->use_smtp_simple;
        $mail->from_email = $request->from_email;
        $mail->replay_email = $request->replay_email;
        $mail->status = $status;
        $mail->created_by = Auth::user()->id;
        $mail->created_at = date("Y-m-d H:i:s");
        $mail->updated_by = Auth::user()->id;
        $mail->updated_at = date("Y-m-d H:i:s");
        $mail->save();

        return redirect()->route('mail-integrations')->with('success','Mail Integrations Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(70, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Mail Integrations"; 
            $data['mail'] = EmailIntegrations::find($id);
            return view('mail-integrations.edit', $data); 
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'smtp_user_name' => 'required',
            'smtp_password' => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'use_smtp_simple' => 'required',
            'from_email' => 'required',
            'replay_email' => 'required',
            'status' => 'required'
        ]);

        

        // echo '<pre>'; print_r($request->all()); exit;
        $mail = EmailIntegrations::find($id);
        if($request->status != $mail->status)
        {
            if($request->status == 1)
            {
                $mails = EmailIntegrations::where('society_id', Auth::user()->society_id)->where('status', 1)->first();
                if(!empty($mails))
                {
                    $mails->status = 0;
                    $mails->save();
                }
            }
        }
        $mail->society_id = Auth::user()->society_id;
        $mail->smtp_user_name = $request->smtp_user_name;
        $mail->smtp_password = $request->smtp_password;
        $mail->smtp_host = $request->smtp_host;
        $mail->smtp_port = $request->smtp_port;
        $mail->use_smtp_simple = $request->use_smtp_simple;
        $mail->from_email = $request->from_email;
        $mail->replay_email = $request->replay_email;
        $mail->status = $request->status;
        $mail->created_by = Auth::user()->id;
        $mail->created_at = date("Y-m-d H:i:s");
        $mail->updated_by = Auth::user()->id;
        $mail->updated_at = date("Y-m-d H:i:s");
        $mail->save();

        return redirect()->route('mail-integrations')->with('success','Mail Integrations Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(71, $permissions))
        {
            $mail = EmailIntegrations::find($id);
            $mail->delete();
            return redirect()->route('mail-integrations')->with('success','Mail Integrations Successfully deleted.');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function status($id, $status, $socity_id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(76, $permissions))
        {
            if($status == 1)
            {
                $mails = EmailIntegrations::where('society_id', $socity_id)->where('status', 1)->first();
                if(!empty($mails))
                {
                    $mails->status = 0;
                    $mails->save();
                }
            }

            if($status == 0)
            {
                $mails = EmailIntegrations::where('society_id', $socity_id)->where('status', 0)->first();
                if(!empty($mails))
                {
                    $mails->status = 1;
                    $mails->save();
                }
            }

            $mail = EmailIntegrations::find($id);
            $mail->status = $status;
            $mail->save();

            return redirect()->route('mail-integrations')->with('success','Mail Integrations Status Change Successfully.');
        }
        else
        {
            return redirect()->back();   
        }
    }
}
