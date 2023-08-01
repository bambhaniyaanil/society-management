<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CampaignMail;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;
 
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\ReceiptsVoucher;
use App\Society;
use App\Invoice;
use App\Ledger;
use Mail;

class CronJobController extends Controller
{
    public function sendMail()
    {
        $campigans = CampaignMail::where('status', 0)->get();
        foreach($campigans as $campigan)
        {
            if($campigan->type == 'receipt')
            {
                $data['receipt'] = ReceiptsVoucher::where('id', $campigan->ids)->first();
                $data['socity'] = Society::where('id', $campigan->society_id)->first();
                $user['from'] = $data['socity']->emailid;
                $user['to'] = $data['receipt']->toLedger->email_id;
                $subject = 'E-RECEIPT of '.$data['receipt']->toLedger->wing_flat_no;
                try {
                    // echo '<pre>'; print_r($data); exit;
                    // echo $user['to']; exit;
                    Mail::send('receipts-voucher/mail', $data, function($msg) use ($user, $subject){
                        $msg->to($user['to']);
                        $msg->subject($subject);
                    });
                    if (Mail::failures()) {
                        $view = view('receipts-voucher/mail', $data);
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= 'From:'.$user['from'] . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        mail($user['to'],$subject,$view,$headers);
                        // echo 'Sorry! Please try again latter';
                        // exit;
                    }
                }
                catch (Throwable $ex) {
                    $view = view('receipts-voucher/mail', $data);
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From:'.$user['from'] . "\r\n";
                    $headers .= 'Cc: myboss@example.com' . "\r\n";
                    mail($user['to'],$subject,$view,$headers);
                    // echo 'Sorry! Your mail credentials is wrong please check credentials.';
                    // exit;
                }
            }
            else{
                $invoice = Invoice::where('id', $campigan->ids)->first();
                $socity = Society::where('id', $campigan->society_id)->first();

                $toledgers = json_decode($invoice->to_ledger, true);
                $toledgers_array = [];
                $total_amount = 0;
                $intrest_amount = 0;
                $total_due_amount = 0;
                foreach ($toledgers as $k => $toledger) {
                    $ledger = Ledger::where('id', $toledger['to_ledger_id'])->first();
                    if ($ledger->name != 'Interest Amount') {
                        $toledgers_array[$k]['name'] = $ledger->name;
                        $toledgers_array[$k]['amount'] = $toledger['amount'];
                        $total_amount += $toledger['amount'];
                    } else {
                        $intrest_amount += $toledger['amount'];
                    }
                }
                $arrears = 0;
                if (!empty($invoice->arrears) && $invoice->arrears != 0) {
                    $arrears = $invoice->arrears;
                }
                $total_due_amount = $total_amount + $arrears + $intrest_amount;
                $data = [
                    'society_name' => $socity->society_name,
                    'society_name_number' => $socity->society_name_number,
                    'society_name_date' => date('m-d-Y', strtotime($socity->society_name_date)),
                    'society_address' => $socity->address,
                    'unit_no' => $invoice->byLedger->wing_flat_no,
                    'area_sq_mtr' => $invoice->byLedger->area_sq_mtr,
                    'area_sq_ft' => $invoice->byLedger->area_sq_ft,
                    'bill_no' => $invoice->bill_no,
                    'name' => $invoice->byLedger->name,
                    'bill_date' => date('d-m-Y', strtotime($invoice->bill_date)),
                    'bill_period' => $invoice->bill_period,
                    'due_date' => date('d-m-Y', strtotime($invoice->due_date)),
                    'to_ledger' => $toledgers_array,
                    'total_amount' => $total_amount,
                    'arrears' => $arrears,
                    'interest_amount' => $intrest_amount,
                    'total_due_amount_payable' => $total_due_amount,
                    'billing_notes' => $socity->notice,
                    'from' => $socity->emailid,
                    'to' => $invoice->byLedger->email_id
                ];

                $user['from'] = $data['from'];
                $user['to'] = $data['to'];
                $data['data'] = $data;
                $subject = 'E-INVOICE of ' . $invoice->byLedger->wing_flat_no;
                
                try {
                    Mail::send('invoice/mail', $data, function ($msg) use ($user, $subject) {
                        // $msg->from($user['from']);
                        $msg->to($user['to']);
                        $msg->subject($subject);
                    });
                    if (Mail::failures()) {
                        $view = view('invoice/mail', $data);
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= 'From:'.$user['from'] . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        mail($user['to'],$subject,$view,$headers);
                    }
                } catch (Throwable $ex) {
                    $view = view('invoice/mail', $data);
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= 'From:'.$user['from'] . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    mail($user['to'],$subject,$view,$headers);
                }
            }

            $campigan->status = 1;
            $campigan->save();
        }
        echo 'Mail Send Successfully';
    }
}
