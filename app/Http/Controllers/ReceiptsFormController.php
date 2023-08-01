<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Society;
use App\Ledger;
use App\ReceiptsForm;

class ReceiptsFormController extends Controller
{
    public function receiptForm()
    {
        $societys = Society::where('status', 1)->get();

        $data['societys'] = $societys;

        return view('receipts-form', $data);
    }

    public function receiptFormSave(Request $request)
    {
        $validated = $request->validate([
            'ledger' => 'required',
            'society' => 'required',
            'bank_name' => 'required',
            'amount' => 'required',
            'submit_date' => 'required',
            'email' => 'required',
            'mobile_number' => 'required'
        ]);

        $rform = new ReceiptsForm();
        $rform->ledger_id = $request->ledger;
        $rform->society_id = $request->society;
        $rform->bank_name = $request->bank_name;
        $rform->email = $request->email;
        $rform->mobile_number = $request->mobile_number;
        $rform->check_transaction_no = $request->check_transaction_no;
        $rform->amount = $request->amount;
        $rform->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $rform->save();
        return redirect()->route('receipt-form')->with('success', 'Receipt Form Successfully created.');
    }

    public function receiptFormLedger($id)
    {
        $data = array();
        $data['ledgers'] = Ledger::where('status', 1)->where('society_id', $id)->get();
        return json_encode($data, true);
    }
}
