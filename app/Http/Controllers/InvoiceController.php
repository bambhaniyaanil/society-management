<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Ledger;
use App\Invoice;
use App\Society;
use Auth;
use Mail;
use Response;
use Session;
use App\SociatyAccountEntry;
use App\Exports\ExportInvoice;
use App\Imports\ImportInvoice;
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

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (\Auth::check()) {
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(49, $permissions))
            {
                $data = array();
                $data['page_title'] = "Invoice";
                $data['sub_title'] = "Invoice List";

                $from_date = Session::get('ifrom_date');
                $to_date = Session::get('ito_date');
                $search = Session::get('isearch');

                if ((isset($from_date) && !empty($from_date)) || (isset($to_date) && !empty($to_date)) || (isset($search) && !empty($search))) {
                    $data['invoices'] = Invoice::with('byLedger')->where('society_id', Auth::user()->society_id)
                        ->where(function ($que) use ($from_date, $to_date) {
                            if ($from_date != '' && $to_date != '') {
                                $que->whereBetween('bill_date', [$from_date, $to_date]);
                            }
                        })
                        ->where(function ($sq) use ($search) {
                            $sq->whereHas('byLedger', function ($q) use ($search) {
                                if ($search != '') {
                                    $q->where('name', 'LIKE', '%' . $search . '%')
                                        ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                                }
                            });
                        });
                    $limit = $request->limit;
                    if ($limit == '') {
                        $limit = 10;
                    }
                    if ($limit == 'all') {
                        $data['invoices'] = $data['invoices']->orderBy('bill_date', 'asc')->get();
                    } else {
                        $data['invoices'] = $data['invoices']->orderBy('bill_date', 'asc')->paginate($limit);
                    }
                } else {
                    $limit = $request->limit;
                    if ($limit == '') {
                        $limit = 10;
                    }
                    if ($limit == 'all') {
                        $data['invoices'] = Invoice::where('user_id', Auth::user()->id)->where('society_id', Auth::user()->society_id)->orderBy('bill_date', 'asc')->get();
                    } else {
                        $data['invoices'] = Invoice::where('user_id', Auth::user()->id)->where('society_id', Auth::user()->society_id)->orderBy('bill_date', 'asc')->paginate($limit);
                    }
                }
                return view('invoice/index', $data);
            }
            else{
                return redirect()->back();
            }
        } else {
            return redirect('/login');
        }
    }

    public function create()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(50, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Invoice";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            return view('invoice/create', $data);
        }
        else{
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $invoice_data = Invoice::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['society_id'] = Auth::user()->society_id;
        $data['bill_date'] = date('Y-m-d', strtotime($data['bill_date']));
        $data['due_date'] = date('Y-m-d', strtotime($data['due_date']));
        $data['to_ledger'] = json_encode($data['ledger']);
        if (!empty($invoice_data)) {
            $data['serial_number'] = $invoice_data->serial_number + 1;
        } else {
            $data['serial_number'] = 1;
        }
        // echo '<pre>'; print_r($data['ledger']); exit;
        $save = Invoice::create($data);
        $id = $save->id;
        if (!empty($sae_data)) {
            $serial_number = $sae_data->serial_number + 1;
        } else {
            $serial_number = 1;
        }
        foreach ($data['ledger'] as $ledger) {
            $sme = new SociatyAccountEntry();
            $sme->by_ledger_id = $request->by_ledger;
            $sme->to_ledger_id = $ledger['to_ledger_id'];
            $sme->society_id = Auth::user()->society_id;
            $sme->added_user_id = Auth::user()->id;
            $sme->amount = $ledger['amount'];
            $sme->submit_date = date('Y-m-d', strtotime($data['bill_date']));;
            $sme->serial_number = $serial_number;
            $sme->refrance_voucher_id = $id;
            $sme->voucher_type = 'invoice';
            $sme->status = $request->status;
            $sme->narration = $request->narration;
            $sme->save();
        }
        return redirect()->route('invoice')->with('success', 'Invoice Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(51, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Invoice";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            $data['invoice'] = Invoice::where('id', $id)->first();
            return view('invoice/edit', $data);
        }
        else{
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['to_ledger'] = json_encode($data['ledger']);
        // echo '<pre>'; print_r($data); exit;
        $invoice = Invoice::where('id', $id)->first();
        $invoice->by_ledger = $data['by_ledger'];
        $invoice->to_ledger = $data['to_ledger'];
        $invoice->by_ledger_amount = $data['by_ledger_amount'];
        $invoice->bill_no = $data['bill_no'];
        $invoice->bill_period = $data['bill_period'];
        $invoice->bill_date = date('Y-m-d', strtotime($data['bill_date']));
        $invoice->due_date = date('Y-m-d', strtotime($data['due_date']));
        $invoice->narration = $data['narration'];
        $invoice->arrears = $data['arrears'];
        $invoice->status = $data['status'];
        $save = $invoice->save();

        $update_ids = [];
        $submit_date = date('Y-m-d');
        foreach ($data['ledger'] as $ledger) {
            $sae = SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'invoice')->where('to_ledger_id', $ledger['to_ledger_id'])->first();
            if (!empty($sae)) {
                $submit_date = $sae->submit_date;
                $sae->by_ledger_id = $request->by_ledger;
                $sae->to_ledger_id = $ledger['to_ledger_id'];
                $sae->amount = $ledger['amount'];
                $sae->narration = $request->narration;
                $sae->save();
            } else {
                $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
                if (!empty($sae_data)) {
                    $serial_number = $sae_data->serial_number + 1;
                } else {
                    $serial_number = 1;
                }
                $sme = new SociatyAccountEntry();
                $sme->by_ledger_id = $request->by_ledger;
                $sme->to_ledger_id = $ledger['to_ledger_id'];
                $sme->society_id = Auth::user()->society_id;
                $sme->added_user_id = Auth::user()->id;
                $sme->amount = $ledger['amount'];
                $sme->submit_date = date('Y-m-d', strtotime($data['bill_date']));;
                $sme->serial_number = $serial_number;
                $sme->refrance_voucher_id = $id;
                $sme->voucher_type = 'invoice';
                $sme->status = $request->status;
                $sme->narration = $request->narration;
                $sme->save();
            }
            $update_ids[] = $ledger['to_ledger_id'];
        }

        $sae_ids = SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'invoice')->get(['id', 'to_ledger_id']);
        foreach ($sae_ids as $sid) {
            if (!in_array($sid->to_ledger_id, $update_ids)) {
                SociatyAccountEntry::where('id', $sid->id)->delete();
            }
        }
        return redirect()->route('invoice')->with('success', 'Invoice Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(52, $permissions))
        {
            Invoice::where('id', $id)->delete();
            SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'invoice')->delete();
            return redirect()->route('invoice')->with('success', 'Invoice deleted successfully');
        }
        else{
            return redirect()->back();
        }
    }

    public function view($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(58, $permissions))
        {
            $data = array();
            $data['page_title'] = "View Invoice";
            $data['invoice'] = Invoice::where('id', $id)->first();
            $data['socity'] = Society::where('id', Auth::user()->society_id)->first();
            return view('invoice/view', $data);
        }
        else{
            return redirect()->back();
        }
    }

    public function send($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        
        
                
        if(in_array(55, $permissions))
        {
            $invoice = Invoice::where('id', $id)->first();
            $socity = Society::where('id', Auth::user()->society_id)->first();
            
            $toledgers = json_decode($invoice->to_ledger, true);
            $toledgers_array = [];
            $total_amount = 0;
            $intrest_amount = 0;
            $total_due_amount = 0;
            foreach($toledgers as $k => $toledger)
            {
                $ledger = Ledger::where('id', $toledger['to_ledger_id'])->first();
                if($ledger->name != 'Interest Amount')
                {
                    $toledgers_array[$k]['name'] = $ledger->name;
                    $toledgers_array[$k]['amount'] = $toledger['amount'];
                    $total_amount += $toledger['amount'];
                }
                else
                {
                    $intrest_amount += $toledger['amount'];
                }
            }
            $arrears = 0;
            if(!empty($invoice->arrears) && $invoice->arrears != 0)
            {
                $arrears = $invoice->arrears;
            }
            $total_due_amount = $total_amount + $arrears + $intrest_amount;
            $data = [
                'society_name' => $socity->society_name,
                'society_name_number' => $socity->society_name_number,
                'society_name_date' => date('d-m-Y',strtotime($socity->society_name_date)),
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
            $from_name = $socity->society_name;
            
            $subject = 'E-INVOICE of '.$invoice->byLedger->wing_flat_no;
            
            try {
                Mail::send('invoice/mail', $data, function($msg) use ($user,$subject, $from_name){
                    $msg->from($address = 'noreply@domain.com', $name = $from_name);
                    $msg->to($user['to']);
                    $msg->subject($subject);
                });
                if (Mail::failures()) {
                    // return redirect()->route('invoice')->with('error','Sorry! Please try again latter');
                    
                    $view = view('invoice/mail', $data);
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: $from_name" . " <" . $user['from'] . ">" . "\r\n";
                    $headers .= 'Cc: '.$user['from'] . "\r\n";
                    
                    mail($user['to'],$subject,$view,$headers);
                    return redirect()->route('invoice')->with('success','Invoice Mail Send Succesfully');
                }
                return redirect()->route('invoice')->with('success','Invoice Mail Send Succesfully');
            }
            catch (Throwable $ex) {
                // return redirect()->route('invoice')->with('error','Sorry! Your mail credentials is wrong please check credentials.');
                
                $view = view('invoice/mail', $data);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: $from_name" . " <" . $user['from'] . ">" . "\r\n";
                $headers .= "Cc: ".$user['from'] . "\r\n";
                
                mail($user['to'],$subject,$view,$headers);
                return redirect()->route('invoice')->with('success','Invoice Mail Send Succesfully');
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

        if(in_array(57, $permissions))
        {
            $path1  = base_path();
            $path1 = $path1 . '/SocietyPDF/mail_pdfs';
            $files = File::files($path1);

            foreach ($files as $key => $value) {
                unlink($value);
            }

            $url = 'http://localhost/dgsociety/SocietyPDF/invoice_pdf.php';
            // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/invoice_pdf.php';
            $invoice = Invoice::where('id', $id)->first();
            $socity = Society::where('id', Auth::user()->society_id)->first();
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
            $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;
            $data = [
                'society_name' => str_replace(',', ' & ', $socity->society_name),
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
                'arrears' => $invoice->arrears,
                'interest_amount' => $intrest_amount,
                'total_due_amount_payable' => $total_due_amount,
                'billing_notes' => $socity->notice,
                'response_type' => 'view'
            ];
            // echo '<pre>'; print_r($data); exit;
            $data = json_encode($data);
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            // // Optional Authentication:
            // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

            // curl_setopt($curl, CURLOPT_URL, $url);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // $result = curl_exec($curl);
            // curl_close($curl);

            $ch = curl_init($url);
            # Setup request to send json via POST.
            $invoice_data = json_encode(array($data));

            curl_setopt($ch, CURLOPT_POSTFIELDS, $invoice_data);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);
            $path  = config('app.url');
            $path = $path . '/SocietyPDF/mail_pdfs/' . $result;
            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/stream',
                'Content-Disposition' => 'inline; filename="' . $result . '"'
            ]);
            // echo '<pre>'; print_r($result); exit;
            // return redirect()->route('invoice')->with('success','Invoice download successfully');
        }
        else{
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Invoice";
        $data['sub_title'] = "Invoice List";
        if ($request->ifrom_date != '') {
            if ($request->ito_date == '') {
                return redirect()->route('invoice')->with('error', 'The to bill date field is required');
            }
        }

        if ($request->ifrom_date == '') {
            if ($request->ito_date != '') {
                return redirect()->route('invoice')->with('error', 'First from bill date field select');
            }
        }

        $from_date = '';
        $to_date = '';
        if ($request->ifrom_date != '') {
            $from_date = date('Y-m-d', strtotime($request->ifrom_date));
        }
        if ($request->ito_date != '') {
            $to_date = date('Y-m-d', strtotime($request->ito_date));
        }
        $search = $request->isearch;
        // echo $from_date.'  '.$to_date; exit;
        Session::put('ifrom_date', $from_date);
        Session::put('ito_date', $to_date);
        Session::put('isearch', $search);
        if ($from_date > $to_date) {
            return redirect()->route('invoice')->with('error', 'Date is invalid please select proper date!');
        }
        $data['invoices'] = Invoice::with('byLedger')->where('society_id', Auth::user()->society_id)
            ->where(function ($que) use ($from_date, $to_date) {
                if ($from_date != '' && $to_date != '') {
                    $que->whereBetween('bill_date', [$from_date, $to_date]);
                }
            })
            ->where(function ($sq) use ($search) {
                $sq->whereHas('byLedger', function ($q) use ($search) {
                    if ($search != '') {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                    }
                });
            });
        // ->paginate(15);
        $limit = $request->limit;
        if ($limit == '') {
            $limit = 10;
        }
        if ($limit == 'all') {
            $data['invoices'] = $data['invoices']->orderBy('bill_date', 'asc')->get();
        } else {
            $data['invoices'] = $data['invoices']->orderBy('bill_date', 'asc')->paginate($limit);
        }
        return view('invoice/index', $data);
    }

    public function export(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(54, $permissions))
        {
            return Excel::download(new ExportInvoice, 'invoice.xlsx');
        }
        else{
            return redirect()->back();
        }
    }

    public function import(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(53, $permissions))
        {
            $fileName = time() . '_' . request()->file->getClientOriginalName();
            request()->file('file')->storeAs('reports', $fileName, 'public');

            Excel::import(new ImportInvoice, request()->file('file'));
            return redirect()->back()->with('success', 'Data Imported Successfully');
        }
        else{
            return redirect()->back();
        }
    }

    public function allInvoceDownload(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(57, $permissions))
        {
            if(empty($request->zip_ids))
            {
                return redirect()->route('invoice')->with('error', 'Please select record');
            }
            $ids = json_decode($request->zip_ids);

            $zip = new ZipArchive;

            $fileName = 'Invoice.zip';
            $path  = base_path();
            $path = $path . '/SocietyPDF/mail_pdfs';

            $files1 = File::files($path);

            foreach ($files1 as $key => $value) {
                unlink($value);
            }

            if(File::exists(public_path('Invoice.zip')))
            {
                unlink(public_path('Invoice.zip'));
            }

            $url = 'http://localhost/dgsociety/SocietyPDF/invoice_pdf.php';
            // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/invoice_pdf.php';
            $invoices = Invoice::where('status', 1)->whereIn('id', $ids)->where('society_id', Auth::user()->society_id)->get();
            // echo '<pre>'; print_r($invoices->toArray()); exit;
            $socity = Society::where('id', Auth::user()->society_id)->first();

            foreach ($invoices as $invoice) {
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
                $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;
                $data = [
                    'society_name' => str_replace(',', ' & ', $socity->society_name),
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
                    'arrears' => $invoice->arrears,
                    'interest_amount' => $intrest_amount,
                    'total_due_amount_payable' => $total_due_amount,
                    'billing_notes' => $socity->notice,
                    'response_type' => 'view'
                ];
                $data = json_encode($data);

                $ch = curl_init($url);
                $invoice_data = json_encode(array($data));

                curl_setopt($ch, CURLOPT_POSTFIELDS, $invoice_data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                # Return response instead of printing.
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                # Send request.
                $result = curl_exec($ch);
                curl_close($ch);
            }




            if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
                $files = File::files($path);

                foreach ($files as $key => $value) {
                    // echo $value; exit;
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }

                $zip->close();
            }
            return response()->download(public_path($fileName));
        }
        else{
            return redirect()->back();
        }
    }

    public function whatsapp($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(56, $permissions))
        {
            $result = $this->createPDF($id);
            $invoice = Invoice::where('id', $id)->first();
            $toledgers = json_decode($invoice->to_ledger, true);
            $total_amount = 0;
            $intrest_amount = 0;
            $total_due_amount = 0;
            foreach ($toledgers as $k => $toledger) {
                $ledger = Ledger::where('id', $toledger['to_ledger_id'])->first();
                if ($ledger->name != 'Interest Amount') {
                    $total_amount += $toledger['amount'];
                } else {
                    $intrest_amount += $toledger['amount'];
                }
            }
            $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;

            $socity = Society::where('id', Auth::user()->society_id)->first();

            $path  = config('app.url');
            $path = $path . '/SocietyPDF/mail_pdfs/' . $result;
            // echo $msg; exit;
            $whats_app_number = explode(',', $invoice->byLedger->whats_app_number);
            $length = strlen($whats_app_number[0]);
            if ($length == 12) {
                $whatsapp_number = '+' . $whats_app_number[0];
            } else {
                $whatsapp_number = '+91' . $whats_app_number[0];
            }
            $bill_date = date('d-m-Y', strtotime($invoice->bill_date));
            $due_date = date('d-m-Y', strtotime($invoice->due_date));
            // $url = 'https://api.whatsapp.com/send?phone=+916356686903&text='.nl2br($msg);
            // Redirect::away($url);
            return response()->json(array('status' => true, 'name' => $invoice->byLedger->name, 'wing_flat_no' => $invoice->byLedger->wing_flat_no, 'total_due_amount' => $total_due_amount, 'bill_date' => $bill_date, 'due_date' => $due_date, 'society_name' => $socity->society_name, 'link' => $path, 'whatsapp_number' => $whatsapp_number));
        }
        else{
            return redirect()->back();
        }
    }

    public function createPDF($id)
    {
        $path1  = base_path();
        $path1 = $path1 . '/SocietyPDF/mail_pdfs';
        $files = File::files($path1);

        foreach ($files as $key => $value) {
            unlink($value);
        }

        $url = 'http://localhost/dgsociety/SocietyPDF/invoice_pdf.php';
        // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/invoice_pdf.php';
        $invoice = Invoice::where('id', $id)->first();
        $socity = Society::where('id', Auth::user()->society_id)->first();
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
        $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;
        $data = [
            'society_name' => str_replace(',', ' & ', $socity->society_name),
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
            'arrears' => $invoice->arrears,
            'interest_amount' => $intrest_amount,
            'total_due_amount_payable' => $total_due_amount,
            'billing_notes' => $socity->notice,
            'response_type' => 'view'
        ];
        // echo '<pre>'; print_r($data); exit;
        $data = json_encode($data);

        $ch = curl_init($url);
        # Setup request to send json via POST.
        $invoice_data = json_encode(array($data));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $invoice_data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function deleteAll(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(52, $permissions))
        {
            if (!empty($request->ids)) {
                $ids = json_decode($request->ids);
                foreach ($ids as $id) {
                    Invoice::where('id', $id)->delete();
                    SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'invoice')->delete();
                }
                return redirect()->route('invoice')->with('success', 'Invoice deleted successfully');
            } else {
                return redirect()->route('invoice')->with('error', 'Please select record');
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
        $path = $path . '/assets/demo/invoice-import-demo.csv';
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/stream',
            'Content-Disposition' => 'inline; filename="invoice-import-demo.csv"'
        ]);
    }

    public function bulkSendMail(Request $request)
    {
        $ids = json_decode($request->send_ids);
        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $campigan = new CampaignMail();
                $campigan->type = 'invoice';
                $campigan->ids = $id;
                $campigan->society_id = Auth::user()->society_id;
                $campigan->status = 0;
                $campigan->save();
            }
            return redirect()->route('invoice')->with('success','Invoice Bulk Email Send Successfully');
        }
        else{
            return redirect()->route('invoice')->with('error','Please Select Receipts Voucher');
        }
    }
}