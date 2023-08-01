<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use App\Invoice;
use App\Ledger;
use File;
use Response;
use ZipArchive;
use Mail;
use Session;

class InvoiceDetailsController extends Controller
{
    public function index()
    {
        $societys = Society::where('status', 1)->get();
        $data['societys'] = $societys;
        return view('search-invoice', $data);
    }

    public function searchInvoice(Request $request)
    {
        Session::put('invoice_ledger_name', $request->name);
        Session::put('invoice_wing_flat_no', $request->wing_flat_no);
        $name = preg_replace('/(\s?\&\s?)/', "&", $request->name);
        $name = str_replace('&',',',$name);
        $ledger = Ledger::where('society_id', $request->society)->where('name', 'LIKE', '%'.$name.'%')->where('wing_flat_no', $request->wing_flat_no)->first();
        $invoices = Invoice::where('by_ledger', $ledger->id)->where('society_id', $request->society)->paginate(15);
        $data['invoices'] = $invoices;
        $data['ledger'] = $ledger;
        return view('invoice-list', $data);
    }

    public function download($id)
    {
        $path1  = base_path(); 
        $path1 = $path1.'/SocietyPDF/mail_pdfs';
        $files = File::files($path1);
   
        foreach ($files as $key => $value) {
            unlink($value);
        }

        $url = 'http://localhost/dgsociety/SocietyPDF/invoice_pdf.php';
        // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/invoice_pdf.php';
        $invoice = Invoice::where('id', $id)->first();
        $socity = Society::where('id', $invoice->society_id)->first();
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
        $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;
        $data = [
            'society_name' => str_replace(',', ' & ', $socity->society_name),
            'society_name_number' => $socity->society_name_number,
            'society_name_date' => $socity->society_name_date,
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

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $invoice_data = json_encode( array( $data ) );
        
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $invoice_data );
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

    public function allInvoceDownload($ledger_id, $society_id)
    {
        $zip = new ZipArchive;
   
        $fileName = 'Invoice.zip';
        $path  = base_path(); 
        $path = $path.'/SocietyPDF/mail_pdfs';

        $files1 = File::files($path);
   
        foreach ($files1 as $key => $value) {
            unlink($value);
        }


        $url = 'http://localhost/dgsociety/SocietyPDF/invoice_pdf.php';
        // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/invoice_pdf.php';
        $invoices = Invoice::where('status', 1)->where('by_ledger', $ledger_id)->get();
        $socity = Society::where('id', $society_id)->first();

        foreach($invoices as $invoice)
        {
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
            $total_due_amount = $total_amount + $invoice->arrears + $intrest_amount;
            $data = [
                'society_name' => str_replace(',', ' & ', $socity->society_name),
                'society_name_number' => $socity->society_name_number,
                'society_name_date' => $socity->society_name_date,
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

            $ch = curl_init( $url );
            $invoice_data = json_encode( array( $data ) );
            
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $invoice_data );
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
                // echo $value; exit;
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
             
            $zip->close();
        }
        return response()->download(public_path($fileName));
    }

    public function invoiceSend($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $socity = Society::where('id', $invoice->society_id)->first();
        
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
            'society_name_date' => $socity->society_name_date,
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
        Mail::send('invoice/mail', $data, function($msg) use ($user){
            $msg->from($user['from']);
            $msg->to($user['to']);
            $msg->subject('Invoice');
        });
        return response()->json(array('status' => true, 'msg' => 'Invoice Mail Send Succesfully'));
    }

    public function view($id)
    {
        $data = array();
        $data['invoice'] = Invoice::where('id', $id)->first();
        $data['socity'] = Society::where('id', $data['invoice']->society_id)->first();
        return view('invoice-view', $data);
    }
}
