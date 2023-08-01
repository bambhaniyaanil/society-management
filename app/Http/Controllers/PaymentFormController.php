<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use App\Ledger;
use App\PaymentForm;

class PaymentFormController extends Controller
{
    public function paymentForm()
    {
        $societys = Society::where('status', 1)->get();

        $data['societys'] = $societys;

        return view('payment-form', $data);
    }

    public function paymentFormSave(Request $request)
    {
        $validated = $request->validate([
            'ledger' => 'required',
            'society' => 'required',
            'bank_name' => 'required',
            'amount' => 'required',
            'submit_date' => 'required',
        ]);

        $rform = new PaymentForm();
        $rform->ledger_id = $request->ledger;
        $rform->society_id = $request->society;
        $rform->bank_name = $request->bank_name;
        $rform->email = $request->email;
        $rform->mobile_number = $request->mobile_number;
        $rform->check_transaction_no = $request->check_transaction_no;
        $rform->amount = $request->amount;
        $rform->narration = $request->narration;
        $rform->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $rform->save();
        return redirect()->route('payment-form')->with('success', 'Payment Form Successfully created.');
    }
}
