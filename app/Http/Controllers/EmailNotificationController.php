<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Ledger;
use Mail;
use App\Mail\send;
use File;

class EmailNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::check()) {
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(91, $permissions))
            {
                $data = array();
                $data['page_title'] = "Email Notification";
                $data['sub_title'] = "Email Notification";
                $emails = Ledger::where('society_id', Auth::user()->society_id)->get(['email_id', 'name']);
                $s_emails = array();
                foreach($emails as $value)
                {
                    if(!empty($value->email_id))
                    {
                        $email = explode(',', $value->email_id);
                        foreach($email as $k => $e)
                        {
                            if(!in_array($e, $s_emails))
                            {
                                $s_emails[$k]['email'] = $e;
                                $s_emails[$k]['name'] = $value->name;
                            }
                        }
                    }
                }
                $data['emails'] = $s_emails;
                return view('email-notification/index', $data);
            }
            else
            {
                return redirect()->back();
            }
        } else {
            return redirect('/login');
        }
    }

    public function send(Request $request)
    {
        $path1  = public_path();
        $path1 = $path1 . '/attach';
        $files = File::files($path1);

        foreach ($files as $key => $value) {
            unlink($value);
        }

        $subject = $request->subject;
        $files = [];
        if (isset($request->file) && !empty($request->file)) {
            foreach ($request->file as $file) {
                $imageName = time() . '.' . $file->extension();
                $file->move(public_path('attach'), $imageName);
                $files[] = public_path() . '/attach/' . $imageName;
            }
        }
        $body = $request->notice;
        $data['subject'] = $subject;
        $data['files'] = $files;
        $data['body'] = $body;
        foreach ($request->emails as $to) {
            Mail::to($to)->send(new send($data));
            if (Mail::failures()) {
                return redirect()->route('email-notification')->with('error', 'Sorry! Please try again latter');
            }
        }
        return redirect()->route('email-notification')->with('success', 'Mail Send Succesfully');
    }
}