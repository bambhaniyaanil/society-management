<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\PaymentVoucher;
use App\Ledger;
use App\SociatyAccountEntry;
use Session;
use DB;
use App\Exports\ExportPaymentVoucher;
use App\Imports\ImportPaymentVoucher;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\Society;
use File;
use App\PaymentForm;

class PaymentVoucherController extends Controller
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

            if(in_array(15, $permissions))
            {
                $data = array();
                $data['page_title'] = "Payment Voucher";
                $data['sub_title'] = "Payment Voucher List";
                $from_date = Session::get('from_date');
                $to_date = Session::get('to_date');
                $search = Session::get('pvsearch');

                if ((isset($from_date) && !empty($from_date)) || (isset($to_date) && !empty($to_date)) || (isset($search) && !empty($search))) {
                    $data['payment_voucher'] = PaymentVoucher::where('society_id', Auth::user()->society_id)
                        ->where(function ($q) use ($from_date, $to_date) {
                            if ($from_date != '' && $to_date != '') {
                                $q->whereBetween('submit_date', [$from_date, $to_date]);
                            }
                        })
                        ->where(function ($sq) use ($search) {
                            $sq->whereHas('fromLedger', function ($q) use ($search) {
                                if ($search != '') {
                                    $q->where('name', 'LIKE', '%' . $search . '%')
                                        ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                                }
                            })
                                ->orwhereHas('toLedger', function ($q2) use ($search) {
                                    if ($search != '') {
                                        $q2->where('name', 'LIKE', '%' . $search . '%')
                                            ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                                    }
                                });
                        });
                    $limit = $request->limit;
                    if ($limit == '') {
                        $limit = 10;
                    }
                    if ($limit == 'all') {
                        $data['payment_voucher'] = $data['payment_voucher']->orderBy('id', 'desc')->get();
                    } else {
                        $data['payment_voucher'] = $data['payment_voucher']->orderBy('id', 'desc')->paginate($limit);
                    }
                } else {
                    $limit = $request->limit;
                    if ($limit == '') {
                        $limit = 10;
                    }
                    if ($limit == 'all') {
                        $data['payment_voucher'] = PaymentVoucher::where('society_id', Auth::user()->society_id)->orderBy('id', 'desc')->get();
                    } else {
                        $data['payment_voucher'] = PaymentVoucher::where('society_id', Auth::user()->society_id)->orderBy('id', 'desc')->paginate($limit);
                    }
                }
                // $data['societys'] = $societys;
                return view('payment-voucher/index', $data);
            }
            else
            {
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

        if(in_array(16, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Payment Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            return view('payment-voucher/create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $payment_data = PaymentVoucher::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
        $data = $request->all();
        $data['added_user_id'] = Auth::user()->id;
        $data['society_id'] = Auth::user()->society_id;
        $data['submit_date'] = date('Y-m-d', strtotime($request->submit_date));
        if (!empty($payment_data)) {
            $data['serial_number'] = $payment_data->serial_number + 1;
        } else {
            $data['serial_number'] = 1;
        }
        $data['submit_date'] = date('Y-m-d', strtotime($data['submit_date']));

        if (!empty($sae_data)) {
            $serial_number = $sae_data->serial_number + 1;
        } else {
            $serial_number = 1;
        }
        $save = PaymentVoucher::create($data);

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
        $sme->voucher_type = 'payment';
        $sme->status = $request->status;
        $sme->narration = $request->narration;
        $sme->save();
        return redirect()->route('payment-voucher')->with('success', 'Payment Voucher Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(17, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Payment Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            $data['paymentv'] = PaymentVoucher::where('id', $id)->first();
            return view('payment-voucher/edit', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        // echo '<pre>'; print_r($ledger); exit;
        $paymentv = PaymentVoucher::find($id);
        $paymentv->buy_ledger_id = $request->buy_ledger_id;
        $paymentv->to_ledger_id = $request->to_ledger_id;
        $paymentv->amount = $request->amount;
        $paymentv->submit_date = date('Y-d-m', strtotime($request->submit_date));
        $paymentv->narration = $request->narration;
        $paymentv->status = $request->status;
        $paymentv->save();

        $smeb = SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'payment')->first();
        $smeb->by_ledger_id = $request->buy_ledger_id;
        $smeb->to_ledger_id = $request->to_ledger_id;
        $smeb->amount = $request->amount;
        $smeb->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $smeb->narration = $request->narration;
        $smeb->save();

        return redirect()->route('payment-voucher')->with('success', 'Payment Voucher Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(18, $permissions))
        {
            PaymentVoucher::where('id', $id)->delete();
            SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'payment')->delete();
            return redirect()->route('payment-voucher')->with('success', 'Payment Voucher deleted successfully');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Payment Voucher";
        $data['sub_title'] = "Payment Voucher List";
        if ($request->from_date != '') {
            if ($request->to_date == '') {
                return redirect()->route('payment-voucher')->with('error', 'The to date field is required');
            }
        }

        if ($request->from_date == '') {
            if ($request->to_date != '') {
                return redirect()->route('payment-voucher')->with('error', 'First from date field select');
            }
        }

        $from_date = '';
        $to_date = '';
        if ($request->from_date != '') {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date != '') {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $search = $request->search;
        // echo $from_date.'  '.$to_date; exit;
        Session::put('from_date', $from_date);
        Session::put('to_date', $to_date);
        Session::put('pvsearch', $search);
        if ($from_date > $to_date) {
            return redirect()->route('payment-voucher')->with('error', 'Date is invalid please select proper date!');
        }
        $data['payment_voucher'] = PaymentVoucher::with('fromLedger')->with('toLedger')->where('society_id', Auth::user()->society_id)
            ->where(function ($que) use ($from_date, $to_date) {
                if ($from_date != '' && $to_date != '') {
                    $que->whereBetween('submit_date', [$from_date, $to_date]);
                }
            })
            ->where(function ($sq) use ($search) {
                $sq->whereHas('fromLedger', function ($q) use ($search) {
                    if ($search != '') {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                    }
                })
                    ->orwhereHas('toLedger', function ($q2) use ($search) {
                        if ($search != '') {
                            $q2->where('name', 'LIKE', '%' . $search . '%')
                                ->orWhere('wing_flat_no', 'LIKE', '%' . $search . '%');
                        }
                    });
            });
        $limit = $request->limit;
        if ($limit == '') {
            $limit = 10;
        }
        if ($limit == 'all') {
            $data['payment_voucher'] = $data['payment_voucher']->orderBy('submit_date', 'asc')->get();
        } else {
            $data['payment_voucher'] = $data['payment_voucher']->orderBy('submit_date', 'asc')->paginate($limit);
        }
        return view('payment-voucher/index', $data);
    }

    public function export(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(20, $permissions))
        {
            return Excel::download(new ExportPaymentVoucher, 'payment-voucher.xlsx');
        }
        else
        {
            return redirect()->back();   
        }
    }

    public function import(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(19, $permissions))
        {
            Excel::import(new ImportPaymentVoucher, request()->file('file'));
            return redirect()->back()->with('success', 'Data Imported Successfully');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function deleteAll(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(18, $permissions))
        {
            if (!empty($request->ids)) {
                $ids = json_decode($request->ids);
                foreach ($ids as $id) {
                    PaymentVoucher::where('id', $id)->delete();
                    SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'payment')->delete();
                }
                return redirect()->route('payment-voucher')->with('success', 'Payment Voucher deleted successfully');
            } else {
                return redirect()->route('payment-voucher')->with('error', 'Please select record');
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
        $path = $path . '/assets/demo/payment-voucher-demo.csv';
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/stream',
            'Content-Disposition' => 'inline; filename="payment-voucher-demo.csv"'
        ]);
    }

    public function view($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);
        if(in_array(77, $permissions))
        {
            $data = array();
            $data['page_title'] = "View Payment";
            $data['payment'] = PaymentVoucher::where('id', $id)->first();
            $data['socity'] = Society::where('id', Auth::user()->society_id)->first();
            return view('payment-voucher/view', $data);
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
        if(in_array(78, $permissions))
        {
            $path1  = base_path();
            $path1 = $path1 . '/SocietyPDF/mail_pdfs';
            $files = File::files($path1);

            foreach ($files as $key => $value) {
                unlink($value);
            }

            $url = 'http://localhost/dgsociety/SocietyPDF/payment_pdf.php';
            // $url = 'https://jtechnoholic.com/MySocietyAssistant/SocietyPDF/payment_pdf.php';
            $payment = PaymentVoucher::where('id', $id)->first();
            $socity = Society::where('id', Auth::user()->society_id)->first();
            $amount_word = NumberToWordConvert($payment->amount);
            $data = [
                'society_name' => $socity->society_name,
                'society_name_number' => $socity->society_name_number,
                'society_name_date' => date('m-d-Y', strtotime($socity->society_name_date)),
                'society_address' => $socity->address,
                'no' => $payment->serial_number,
                'date' => date('m-d-Y', strtotime($payment->submit_date)),
                'by_ledger_name' => $payment->fromLedger->name,
                'amount_word' => $amount_word,
                'narration' => $payment->narration,
                'unit_no' => $payment->fromLedger->wing_flat_no,
                'amount' => $payment->amount,
                'response_type' => 'view',
            ];
            $ch = curl_init($url);
            # Setup request to send json via POST.
            $receipt_data = json_encode(array($data));

            curl_setopt($ch, CURLOPT_POSTFIELDS, $receipt_data);
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
        }
        else
        {
            return redirect()->back();
        }
    }

    public function paymentForm()
    {
        $data['page_title'] = 'Payment Form Data';
        $data['sub_title'] = 'Payment Form Data List';
        $data['receipt_forms'] = PaymentForm::with('fromLedger')->where('society_id', Auth::user()->society_id)->paginate(15);
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
        return view('payment-form-list', $data);
    }

    public function paymentFormEdit($id)
    {
        $data = array();
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
        $data['page_title'] = "Edit Payment Form Data";
        $data['data'] = PaymentForm::find($id)->first();
        return view('payment-voucher/edit-form', $data);
    }

    public function updateForm(Request $request, $id)
    {
        $form = PaymentForm::find($id)->first();
        $form->ledger_id = $request->ledger;
        $form->bank_name = $request->bank_name;
        $form->email = $request->email;
        $form->mobile_number = $request->mobile_number;
        $form->check_transaction_no = $request->check_transaction_no;
        $form->amount = $request->amount;
        $form->narration = $request->narration;
        $form->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $form->save();

        return redirect()->route('payment-form-list')->with('success','Payment Voucher Form Data Successfully Updated.');
    }

    public function paymentFormDelete($id)
    {
        PaymentForm::where('id',$id)->delete();
        return redirect()->route('payment-form-list')->with('success','Payment Voucher Form Data deleted successfully');
    }
    

    public function paymentFormAccept(Request $request)
    {
        $form = PaymentForm::find($request->form_id)->first();

        $payment_data = PaymentVoucher::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');

        $data['buy_ledger_id'] = $form->ledger_id;
        $data['to_ledger_id'] = $request->ledger;
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
        $data['narration'] = $form->narration;
        $data['status'] = 1;
        $save = PaymentVoucher::create($data);

        $id = $save->id;

        $sme = new SociatyAccountEntry();
        $sme->by_ledger_id = $form->ledger_id;
        $sme->to_ledger_id = $request->ledger;
        $sme->society_id = $form->society_id;
        $sme->added_user_id = Auth::user()->id;
        $sme->amount = $form->amount;
        $sme->submit_date = $data['submit_date'];
        $sme->serial_number = $serial_number;
        $sme->refrance_voucher_id = $id;
        $sme->voucher_type = 'payment';
        $sme->status = 1;
        $sme->narration = $form->narration;
        $sme->save();

        $form->delete();
        return redirect()->route('payment-form-list')->with('success','Payment Voucher Form Data Successfully Accepted.');
    }
}