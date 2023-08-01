<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\ReceiptsVoucher;
use App\Ledger;
use App\SociatyAccountEntry;
use App\Society;
use Session;
use Mail;
use Response;
use App\Exports\ExportReceiptsVoucher;
use App\Imports\ImportReceiptsVoucher;
use Maatwebsite\Excel\Facades\Excel;
use File;
use ZipArchive;
use Redirect;
use App\CampaignMail;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;
 
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\ReceiptsForm;

class ReceiptsVoucherController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(21, $permissions))
            {
                $data = array();
                $data['page_title'] = "Receipts Voucher"; 
                $data['sub_title'] = "Receipts Voucher List";
                // $data['receipts_voucher'] = ReceiptsVoucher::where('status', 1)->get();
                // $data['societys'] = $societys;
                $from_date = Session::get('rvfrom_date');
                $to_date = Session::get('rvto_date');
                $search = Session::get('rvsearch');

                if((isset($from_date) && !empty($from_date)) || (isset($to_date) && !empty($to_date)) || (isset($search) && !empty($search)))
                {
                    $data['receipts_voucher'] = ReceiptsVoucher::where('society_id', Auth::user()->society_id)
                                        ->where(function($q) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $q->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where(function($sq) use ($search)
                                        {
                                            $sq->whereHas('buyLedger', function($q) use ($search)
                                            {
                                                if($search != '')
                                                {
                                                    $q->where('name', 'LIKE', '%'.$search.'%')
                                                    ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');   
                                                }
                                            })
                                            ->orwhereHas('toLedger', function($q2) use ($search)
                                            {
                                                if($search != '')
                                                {
                                                    $q2->where('name', 'LIKE', '%'.$search.'%')
                                                    ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');  
                                                }
                                            });
                                        });
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['receipts_voucher'] = $data['receipts_voucher']->orderBy('submit_date', 'asc')->get();
                    }
                    else{
                        $data['receipts_voucher'] = $data['receipts_voucher']->orderBy('submit_date', 'asc')->paginate($limit);
                    }
                }
                else
                {
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }
                    if($limit == 'all')
                    {
                        $data['receipts_voucher'] = ReceiptsVoucher::where('society_id', Auth::user()->society_id)->orderBy('submit_date', 'asc')->get();
                    }
                    else{
                        $data['receipts_voucher'] = ReceiptsVoucher::where('society_id', Auth::user()->society_id)->orderBy('submit_date', 'asc')->paginate($limit);
                    }
                }
                return view('receipts-voucher/index', $data);
            }
            else{
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

        if(in_array(22, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Receipts Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            return view('receipts-voucher/create', $data);
        }
        else{
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $payment_data = ReceiptsVoucher::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
        $data = $request->all();
        $data['added_user_id'] = Auth::user()->id;
        $data['society_id'] = Auth::user()->society_id;
        $data['submit_date'] = date('Y-m-d', strtotime($request->submit_date));
        if(!empty($payment_data))
        {
            $data['serial_number'] = $payment_data->serial_number + 1;
        }
        else{
            $data['serial_number'] = 1;
        }
        if(!empty($sae_data))
        {
            $serial_number = $sae_data->serial_number + 1;
        }
        else
        {
            $serial_number = 1;
        }

        $save = ReceiptsVoucher::create($data);

        $id = $save->id;

        $sme = new SociatyAccountEntry();
        $sme->by_ledger_id = $request->buy_ledger_id;
        $sme->to_ledger_id = $request->to_ledger_id;
        $sme->society_id = Auth::user()->society_id;
        $sme->added_user_id = Auth::user()->id;
        $sme->amount = $request->amount;
        $sme->submit_date = $data['submit_date'];
        $sme->serial_number = $serial_number;
        $sme->refrance_voucher_id = $id;
        $sme->voucher_type = 'receipts';
        $sme->status = $request->status;
        $sme->narration = $request->narration;
        $sme->save();

        return redirect()->route('receipts-voucher')->with('success','Receipts Voucher Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(23, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Receipts Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            $data['receiptsv'] = ReceiptsVoucher::where('id', $id)->first();
            return view('receipts-voucher/edit', $data);
        }
        else{
            return redirect()->back();   
        }
    }

    public function update(Request $request, $id)
    {
        // echo '<pre>'; print_r($ledger); exit;
        $paymentv = ReceiptsVoucher::find($id);
        $paymentv->buy_ledger_id = $request->buy_ledger_id;
        $paymentv->to_ledger_id = $request->to_ledger_id;
        $paymentv->amount = $request->amount;
        $paymentv->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $paymentv->status = $request->status;
        $paymentv->narration = $request->narration;
        $paymentv->save();

        $smeb = SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'receipts')->first();
        $smeb->by_ledger_id = $request->buy_ledger_id;
        $smeb->to_ledger_id = $request->to_ledger_id;
        $smeb->amount = $request->amount;
        $smeb->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $smeb->narration = $request->narration;
        $smeb->save();

        return redirect()->route('receipts-voucher')->with('success','Receipts Voucher Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(24, $permissions))
        {
            ReceiptsVoucher::where('id',$id)->delete();
            SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'receipts')->delete();
            return redirect()->route('receipts-voucher')->with('success','Receipts Voucher deleted successfully');
        }
        else{
            return redirect()->back();  
        }
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Receipts Voucher"; 
        $data['sub_title'] = "Receipts Voucher List";
        if($request->from_date != '')
        {
            if($request->to_date == '')
            {
                return redirect()->route('receipts-voucher')->with('error','The to date field is required');
            }
        }

        if($request->from_date == '')
        {
            if($request->to_date != '')
            {
                return redirect()->route('receipts-voucher')->with('error','First from date field select');
            }
        }
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $search = $request->search;
        Session::put('rvfrom_date', $from_date);
        Session::put('rvto_date', $to_date);
        Session::put('rvsearch', $search);
        if($from_date > $to_date)
        {
            return redirect()->route('receipts-voucher')->with('error','Date is invalid please select proper date!');
        }
        $data['receipts_voucher'] = ReceiptsVoucher::where('society_id', Auth::user()->society_id)
                                    ->where(function($que) use ($from_date, $to_date){
                                        if($from_date != '' && $to_date != '')
                                        {
                                            $que->whereBetween('submit_date', [$from_date, $to_date]);
                                        }
                                    })
                                    ->where(function($sq) use ($search)
                                    {
                                        $sq->whereHas('buyLedger', function($q) use ($search)
                                        {
                                            if($search != '')
                                            {
                                                $q->where('name', 'LIKE', '%'.$search.'%')
                                                ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');   
                                            }
                                        })
                                        ->orwhereHas('toLedger', function($q2) use ($search)
                                        {
                                            if($search != '')
                                            {
                                                $q2->where('name', 'LIKE', '%'.$search.'%')
                                                ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');   
                                            }
                                        });
                                    });
            $limit = $request->limit;
            if($limit == '')
            {
                $limit = 10;
            }   
            if($limit == 'all')
            {                     
                $data['receipts_voucher'] = $data['receipts_voucher']->orderBy('submit_date', 'asc')->get();
            }
            else{
                $data['receipts_voucher'] = $data['receipts_voucher']->orderBy('submit_date', 'asc')->paginate($limit);
            }
        
        return view('receipts-voucher/index', $data);
    }

    public function view($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(29, $permissions))
        {
            $data = array();
            $data['page_title'] = "View Receipts";
            $data['receipt'] = ReceiptsVoucher::where('id', $id)->first();
            $data['socity'] = Society::where('id', Auth::user()->society_id)->first();
            return view('receipts-voucher/view', $data);
        }
        else
        {
            return redirect()->back();     
        }
    }

    public function send($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        
        $data['receipt'] = ReceiptsVoucher::where('id', $id)->first();
        $data['socity'] = Society::where('id', Auth::user()->society_id)->first();
        $user['from'] = $data['socity']->emailid;
        $user['to'] = $data['receipt']->toLedger->email_id;
        $subject = 'E-RECEIPT of '.$data['receipt']->toLedger->wing_flat_no;
        $from_name = $data['socity']->society_name;
        
        if(in_array(27, $permissions))
        {
            try {
                Mail::send('receipts-voucher/mail', $data, function($msg) use ($user, $subject, $from_name){
                    $msg->from($address = 'noreply@domain.com', $name = $from_name);
                    $msg->to($user['to']);
                    $msg->subject($subject);
                });
                if (Mail::failures()) {
                    // return redirect()->route('receipts-voucher')->with('error','Sorry! Please try again latter');
                    $view = view('receipts-voucher/mail', $data);
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: $from_name" . " <" . $user['from'] . ">" . "\r\n";
                    $headers .= 'Cc: '.$user['from'] . "\r\n";
                    
                    mail($user['to'],$subject,$view,$headers);
                    return redirect()->route('receipts-voucher')->with('success','Receipt Mail Send Succesfully');
                }
                return redirect()->route('receipts-voucher')->with('success','Receipt Mail Send Succesfully');
            }
            catch (Throwable $ex) {
                $view = view('receipts-voucher/mail', $data);
                $headers = "MIME-Version: 1.0" . "\r\n";
                // $headers = "From: $fromName" . " <" . $from . ">" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: $from_name" . " <" . $user['from'] . ">" . "\r\n";
                $headers .= "Cc:". $user['from'] . "\r\n";
               
                mail($user['to'],$subject,$view,$headers);
                // return redirect()->route('receipts-voucher')->with('error','Sorry! Your mail credentials is wrong please check credentials.');
                return redirect()->route('receipts-voucher')->with('success','Receipt Mail Send Succesfully');
            }
        }
        else
        {
            return redirect()->back();
        }
    }

    public function download($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(30, $permissions))
        {
            $path1  = base_path(); 
            $path1 = $path1.'/SocietyPDF/mail_pdfs';
            $files = File::files($path1);
    
            foreach ($files as $key => $value) {
                unlink($value);
            }

            $url = 'http://localhost/dgsociety/SocietyPDF/receipt_pdf.php';
            // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/receipt_pdf.php';
            $receipt = ReceiptsVoucher::where('id', $id)->first();
            $socity = Society::where('id', Auth::user()->society_id)->first();
            $amount_word = NumberToWordConvert($receipt->amount);
            $data = [
                'society_name' => $socity->society_name,
                'society_name_number' => $socity->society_name_number,
                'society_name_date' => date('d-m-Y', strtotime($socity->society_name_date)),
                'society_address' => $socity->address,
                'no' => $receipt->serial_number,
                'date' => date('d-m-Y', strtotime($receipt->submit_date)),
                'by_ledger_name' => $receipt->toLedger->name,
                'amount_word' => $amount_word,
                'narration' => $receipt->narration,
                'unit_no' => $receipt->toLedger->wing_flat_no,
                'amount' => $receipt->amount,
                'response_type' => 'view',
                'billing_notes' => '1) Subject To Realizations of Cheque / Transactions.
                2) Acknowledgment of This Receipt Being Passed by Allotte / Holders.'
            ];
            $ch = curl_init( $url );
            # Setup request to send json via POST.
            $receipt_data = json_encode( array( $data ) );
            
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $receipt_data );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);

            $path  = config('app.url'); 
            $path = $path.'/SocietyPDF/mail_pdfs/'.$result;
            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/stream',
                'Content-Disposition' => 'inline; filename="'.$result.'"'
            ]);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(26, $permissions))
        {
            return Excel::download(new ExportReceiptsVoucher, 'receipts-voucher.xlsx');
        }
        else{
            return redirect()->back();
        }
    }

    public function import(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(25, $permissions))
        {
            Excel::import(new ImportReceiptsVoucher, request()->file('file'));
            // exit;
            return redirect()->back()->with('success','Data Imported Successfully');
        }
        else{
            return redirect()->back();
        }
    }

    public function allReceiptsDownload(Request $request)
    {
       
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(30, $permissions))
        {
            if(empty($request->zip_ids))
            {
                return redirect()->route('receipts-voucher')->with('error', 'Please select record');
            }
            
            $ids = json_decode($request->zip_ids);

            $zip = new ZipArchive;
    
            $fileName = 'Receipts.zip';
            $path  = base_path(); 
            $path = $path.'/SocietyPDF/mail_pdfs';
            $files1 = File::files($path);
            foreach ($files1 as $key => $value) {
                unlink($value);
            }

            if(File::exists(public_path('Receipts.zip')))
            {
                unlink(public_path('Receipts.zip'));
            }
            $url = 'http://localhost/dgsociety/SocietyPDF/receipt_pdf.php';
            // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/receipt_pdf.php';
            $receipts = ReceiptsVoucher::where('status', 1)->whereIn('id', $ids)->where('society_id', Auth::user()->society_id)->get();
            $socity = Society::where('id', Auth::user()->society_id)->first();

            foreach($receipts as $receipt)
            {
                $amount_word = NumberToWordConvert($receipt->amount);
                $data = [
                    'society_name' => $socity->society_name,
                    'society_name_number' => $socity->society_name_number,
                    'society_name_date' => date('m-d-Y', strtotime($socity->society_name_date)),
                    'society_address' => $socity->address,
                    'no' => $receipt->serial_number,
                    'date' => date('m-d-Y', strtotime($receipt->submit_date)),
                    'by_ledger_name' => $receipt->toLedger->name,
                    'amount_word' => $amount_word,
                    'narration' => $receipt->narration,
                    'unit_no' => $receipt->toLedger->wing_flat_no,
                    'amount' => $receipt->amount,
                    'response_type' => 'view',
                    'billing_notes' => '1) Subject To Realizations of Cheque / Transactions.
                    2) Acknowledgment of This Receipt Being Passed by Allotte / Holders.'
                ];
                
                $ch = curl_init( $url );
                # Setup request to send json via POST.
                $receipt_data = json_encode( array( $data ) );
                
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $receipt_data );
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                # Return response instead of printing.
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                # Send request.
                $result = curl_exec($ch);
                curl_close($ch);
            }

            if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
            {
                $files = File::files($path);
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }                
                $zip->close();
            }
            return response()->download(public_path($fileName));
        }
        else
        {
            return redirect()->back();
        }
    }


    public function whatsapp($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(28, $permissions))
        {
            // $result = $this->createPDF($id);
            $receipt = ReceiptsVoucher::where('id', $id)->first();
            $socity = Society::where('id', Auth::user()->society_id)->first();

            // $path  = config('app.url'); 
            // $path = $path.'/SocietyPDF/mail_pdfs/'.$result;
            // echo $msg; exit;
            $whats_app_number = explode(',', $receipt->toLedger->whats_app_number);

            $length = strlen($whats_app_number[0]);
            if($length == 12)
            {
                $whatsapp_number = '+'.$whats_app_number[0];
            }
            else{
                $whatsapp_number = '+91'.$whats_app_number[0];
            }
            $date = date('d-m-Y', strtotime($receipt->submit_date));
            return response()->json(array('status' => true, 'name' => $receipt->toLedger->name, 'wing_flat_no' => $receipt->toLedger->wing_flat_no, 'amount' => $receipt->amount, 'date' => $date, 'society_name' => $socity->society_name, 'whatsapp_number' => $whatsapp_number));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function createPDF($id)
    {
        $path1  = base_path(); 
        $path1 = $path1.'/SocietyPDF/mail_pdfs';
        $files = File::files($path1);
   
        foreach ($files as $key => $value) {
            unlink($value);
        }

        $url = 'http://localhost/dgsociety/SocietyPDF/receipt_pdf.php';
        // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/receipt_pdf.php';
        $receipt = ReceiptsVoucher::where('id', $id)->first();
        $socity = Society::where('id', Auth::user()->society_id)->first();
        $amount_word = NumberToWordConvert($receipt->amount);
        $data = [
            'society_name' => $socity->society_name,
            'society_name_number' => $socity->society_name_number,
            'society_name_date' => date('d-m-Y', strtotime($socity->society_name_date)),
            'society_address' => $socity->address,
            'no' => $receipt->serial_number,
            'date' => date('d-m-Y', strtotime($receipt->submit_date)),
            'by_ledger_name' => $receipt->toLedger->name,
            'amount_word' => $amount_word,
            'narration' => $receipt->narration,
            'unit_no' => $receipt->toLedger->wing_flat_no,
            'amount' => $receipt->amount,
            'response_type' => 'view',
            'billing_notes' => '1) Subject To Realizations of Cheque / Transactions.
            2) Acknowledgment of This Receipt Being Passed by Allotte / Holders.'
        ];
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $receipt_data = json_encode( array( $data ) );
        
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $receipt_data );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function deleteAll(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(24, $permissions))
        {
            if(!empty($request->ids))
            {
                $ids = json_decode($request->ids);
                foreach($ids as $id)
                {
                    ReceiptsVoucher::where('id',$id)->delete();
                    SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'receipts')->delete();
                }
                return redirect()->route('receipts-voucher')->with('success','Receipts Voucher deleted successfully');
            }
            else{
                return redirect()->route('receipts-voucher')->with('error','Please select record');
            }
        }
        else
        {
            return redirect()->back();
        }
    }
    
    public function demoFile()
    {
        $path  = config('app.url'); 
        $path = $path.'/assets/demo/receipt-voucher-demo.csv';
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/stream',
            'Content-Disposition' => 'inline; filename="receipt-voucher-demo.csv"'
        ]);
    }

    public function receiptForm()
    {
        $data['page_title'] = 'Receipts Form Data';
        $data['sub_title'] = 'Receipts Form Data List';
        $data['receipt_forms'] = ReceiptsForm::with('toLedger')->where('society_id', Auth::user()->society_id)->paginate(15);
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
        return view('receipts-form-list', $data);
    }

    public function receiptFormAccept(Request $request)
    {
        $form = ReceiptsForm::find($request->form_id)->first();

        $payment_data = ReceiptsVoucher::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');

        $data['buy_ledger_id'] = $request->ledger;
        $data['to_ledger_id'] = $form->ledger_id;
        $data['added_user_id'] = Auth::user()->id;
        $data['society_id'] = $form->society_id;
        $data['submit_date'] = $form->submit_date;
        $data['amount'] = $form->amount;
        if(!empty($payment_data))
        {
            $data['serial_number'] = $payment_data->serial_number + 1;
        }
        else{
            $data['serial_number'] = 1;
        }
        if(!empty($sae_data))
        {
            $serial_number = $sae_data->serial_number + 1;
        }
        else
        {
            $serial_number = 1;
        }
        $data['narration'] = $form->check_transaction_no;
        $data['status'] = 1;
        $save = ReceiptsVoucher::create($data);

        $id = $save->id;

        $sme = new SociatyAccountEntry();
        $sme->by_ledger_id = $request->ledger;
        $sme->to_ledger_id = $form->ledger_id;
        $sme->society_id = $form->society_id;
        $sme->added_user_id = Auth::user()->id;
        $sme->amount = $form->amount;
        $sme->submit_date = $data['submit_date'];
        $sme->serial_number = $serial_number;
        $sme->refrance_voucher_id = $id;
        $sme->voucher_type = 'receipts';
        $sme->status = 1;
        $sme->narration = $form->check_transaction_no;
        $sme->save();

        $form->delete();
        return redirect()->route('receipt-form-list')->with('success','Receipts Voucher Form Data Successfully Accepted.');
    }

    public function receiptFormEdit($id)
    {
        $data = array();
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
        $data['page_title'] = "Edit Receipts Form Data";
        $data['data'] = ReceiptsForm::find($id)->first();
        return view('receipts-voucher/edit-form', $data);
    }

    public function updateForm(Request $request, $id)
    {
        $form = ReceiptsForm::find($id)->first();
        $form->ledger_id = $request->ledger;
        $form->bank_name = $request->bank_name;
        $form->email = $request->email;
        $form->mobile_number = $request->mobile_number;
        $form->check_transaction_no = $request->check_transaction_no;
        $form->amount = $request->amount;
        $form->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $form->save();

        return redirect()->route('receipt-form-list')->with('success','Receipts Voucher Form Data Successfully Updated.');
    }

    public function receiptFormDelete($id)
    {
        ReceiptsForm::where('id',$id)->delete();
        return redirect()->route('receipt-form-list')->with('success','Receipts Voucher Form Data deleted successfully');
    }

    public function bulkSendMail(Request $request)
    {
        $ids = json_decode($request->send_ids);
        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $campigan = new CampaignMail();
                $campigan->type = 'receipt';
                $campigan->ids = $id;
                $campigan->society_id = Auth::user()->society_id;
                $campigan->status = 0;
                $campigan->save();
            }
            return redirect()->route('receipts-voucher')->with('success','Receipts Voucher Bulk Email Send Successfully');
        }
        else{
            return redirect()->route('receipts-voucher')->with('error','Please Select Invoice');
        }
    }
}
