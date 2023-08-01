<?php

namespace App\Imports;

use App\PaymentVoucher;
use App\Ledger;
use App\Society;
use App\User;
use App\SociatyAccountEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Auth;
use App\Rules\LedgerNameRule;

class ImportPaymentVoucher implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return[
            'by_ledger' => [
                'required', new LedgerNameRule('paymentvoucher', 'byledger')
            ],
            'to_ledger' => [
                'required', new LedgerNameRule('paymentvoucher', 'toledger')
            ],
            'amount' => [
                'required'
            ],
            'submit_date' => [
                'required'
            ]
        ];
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $bynames = explode(' - ', $row['by_ledger']);
        if(count($bynames) == 2)
        {
            $name = preg_replace('/(\s?\&\s?)/', "&", $bynames[1]);
            $name = str_replace('&',',',$name);
            $by_ledger = Ledger::where('name', $name)->where('wing_flat_no', $bynames[0])->where('society_id', Auth::user()->society_id)->first();
            // echo $ledger; exit;
        }
        else{
            $name = preg_replace('/(\s?\&\s?)/', "&", $bynames[0]);
            $name = str_replace('&',',',$name);
            $by_ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->first();
        }

        $tonames = explode(' - ', $row['to_ledger']);
        if(count($tonames) == 2)
        {
            $name = preg_replace('/(\s?\&\s?)/', "&", $tonames[1]);
            $name = str_replace('&',',',$name);
            $to_ledger = Ledger::where('name', $name)->where('wing_flat_no', $tonames[0])->where('society_id', Auth::user()->society_id)->first();
            // echo $ledger; exit;
        }
        else{
            $name = preg_replace('/(\s?\&\s?)/', "&", $tonames[0]);
            $name = str_replace('&',',',$name);
            $to_ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->first();
        }

        $payment_data = PaymentVoucher::orderBy('id', 'DESC')->first('serial_number');

        if(!empty($payment_data))
        {
            $serial_number = $payment_data->serial_number + 1;
        }
        else{
            $serial_number = 1;
        }

        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
        if(!empty($sae_data))
        {
            $serial_number_sae = $sae_data->serial_number + 1;
        }
        else
        {
            $serial_number_sae = 1;
        }

        $payment_voucher = new PaymentVoucher();
        $payment_voucher->buy_ledger_id = $by_ledger->id;
        $payment_voucher->to_ledger_id = $to_ledger->id;
        $payment_voucher->society_id = Auth::user()->society_id;
        $payment_voucher->added_user_id = Auth::user()->id;
        $payment_voucher->amount = $row['amount'];
        $payment_voucher->submit_date = date('Y-m-d', strtotime($row['submit_date']));
        $payment_voucher->narration = $row['narration'];
        $payment_voucher->serial_number = $serial_number;
        $payment_voucher->save();
            
        return new SociatyAccountEntry([
            'by_ledger_id' => $by_ledger->id,
            'to_ledger_id' => $to_ledger->id,
            'society_id' => Auth::user()->society_id,
            'added_user_id' => Auth::user()->id,   
            'amount' => $row['amount'],
            'submit_date' => date('Y-m-d', strtotime($row['submit_date'])),
            'serial_number' => $serial_number_sae,
            'refrance_voucher_id' => $payment_voucher->id,
            'voucher_type' => 'payment',
            'status' => 1,
            'narration' => $row['narration'],
        ]);
    }
}
