<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use App\ReceiptsVoucher;
use App\Ledger;
use File;
use Response;
use ZipArchive;
use Mail;
use Session;

class ReceiptsDetailsController extends Controller
{
    public function index()
    {
        $societys = Society::where('status', 1)->get();
        $data['societys'] = $societys;
        return view('search-receipts', $data);
    }

    public function searchReceipts(Request $request)
    {
        Session::put('receipts_ledger_name', $request->name);
        Session::put('receipts_wing_flat_no', $request->wing_flat_no);
        $name = preg_replace('/(\s?\&\s?)/', "&", $request->name);
        $name = str_replace('&',',',$name);
        $ledger = Ledger::where('society_id', $request->society)->where('name', 'LIKE', '%'.$name.'%')->where('wing_flat_no', $request->wing_flat_no)->first();
        $receipts_voucher = array();
        if(!empty($ledger))
        {
            $receipts_voucher = ReceiptsVoucher::where('to_ledger_id', $ledger->id)->where('society_id', $request->society)->orderBy('submit_date', 'asc')->paginate(15);
        }
        $data['receipts_voucher'] = $receipts_voucher;
        $data['ledger'] = $ledger;
        return view('receipts-list', $data);
    }

    public function download($id)
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
        $socity = Society::where('id', $receipt->society_id)->first();
        $amount_word = NumberToWordConvert($receipt->amount);
        $data = [
            'society_name' => str_replace(',', ' & ', $socity->society_name),
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

    public function send($id)
    {
        $data['receipt'] = ReceiptsVoucher::where('id', $id)->first();
        $data['socity'] = Society::where('id', $data['receipt']->society_id)->first();
        $user['from'] = $data['socity']->emailid;
        $user['to'] = $data['receipt']->buyLedger->email_id;

        Mail::send('receipts-voucher/mail', $data, function($msg) use ($user){
            $msg->from($user['from']);
            $msg->to($user['to']);
            $msg->subject('Receipt');
        });
        return response()->json(array('status' => true, 'msg' => 'Receipts Mail Send Succesfully'));
    }

    public function view($id)
    {
        $data = array();
        $data['receipt'] = ReceiptsVoucher::where('id', $id)->first();
        $data['socity'] = Society::where('id', $data['receipt']->society_id)->first();
        return view('receipts-view', $data);
    }

    public function allReceiptsDownload($ledger, $society)
    {
        $zip = new ZipArchive;
   
        $fileName = 'Receipts.zip';
        $path  = base_path(); 
        $path = $path.'/SocietyPDF/mail_pdfs';
        $files1 = File::files($path);
        foreach ($files1 as $key => $value) {
            unlink($value);
        }


        $url = 'http://localhost/dgsociety/SocietyPDF/receipt_pdf.php';
        // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/receipt_pdf.php';
        $receipts = ReceiptsVoucher::where('status', 1)->where('to_ledger_id', $ledger)->get();
        $socity = Society::where('id', $society)->first();

        foreach($receipts as $receipt)
        {
            $amount_word = NumberToWordConvert($receipt->amount);
            $data = [
                'society_name' => $socity->society_name,
                'society_name_number' => $socity->society_name_number,
                'society_name_date' => date('d-m-Y', strtotime($socity->society_name_date)),
                'society_address' => $socity->address,
                'no' => $receipt->serial_number,
                'date' => date('d-m-Y', strtotime($receipt->submit_date)),
                'by_ledger_name' => str_replace(',', ' & ', $receipt->toLedger->name),
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
}
