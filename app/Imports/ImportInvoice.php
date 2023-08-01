<?php

namespace App\Imports;

use App\Invoice;
use App\Ledger;
use App\SociatyAccountEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Auth;
use App\Rules\LedgerNameRule;

class ImportInvoice implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return[
            'by_ledger' => [
                'required', new LedgerNameRule('invoice', 'byledger')
            ],
            'to_ledger' => [
                'required', new LedgerNameRule('invoice', 'toledger')
            ],
            'by_ledger_amount' => [
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
        // echo '<pre>'; print_r($row); exit;
        $invoice_data = Invoice::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');

        if(!empty($invoice_data))
        {
            $serial_number = $invoice_data->serial_number + 1;
        }
        else{
            $serial_number = 1;
        }

        $bynames = explode(' - ', $row['by_ledger']);
        if(count($bynames) == 2)
        {
            $name = preg_replace('/(\s?\&\s?)/', "&", $bynames[1]);
            $name = str_replace('&',',',$name);
            $by_ledger = Ledger::where('name', $name)->where('wing_flat_no', $bynames[0])->where('society_id', Auth::user()->society_id)->first();
        }
        else{
            $name = preg_replace('/(\s?\&\s?)/', "&", $bynames[0]);
            $name = str_replace('&',',',$name);
            $by_ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->first();
        }

        $datas = explode(',', $row['to_ledger']);

        $to_ledgers = array();

        foreach($datas as $k => $value)
        {
            $data1 = explode('=', $value); 
            $data = explode(' - ', $data1[0]);
            if(count($data) == 2)
            {
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[1]);
                $name = str_replace('&',',',$name);
                $to_ledger = Ledger::where('name', $name)->where('wing_flat_no', $data[0])->where('society_id', Auth::user()->society_id)->first();
            }
            else
            {
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[0]);
                $name = str_replace('&',',',$name);
                $to_ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->first();
                // echo '<pre>'; print_r($to_ledger->toArray());
            }
            if(!empty($to_ledger))
            {
                $to_ledgers[$k]['to_ledger_id'] = $to_ledger->id;
                $to_ledgers[$k]['amount'] = $data1[1]; 
            }
        }
        // exit;
        $invoice = new Invoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->society_id = Auth::user()->society_id;
        $invoice->by_ledger = $by_ledger->id;
        $invoice->to_ledger = json_encode($to_ledgers);
        $invoice->by_ledger_amount = $row['by_ledger_amount'];
        $invoice->bill_no = $row['bill_no'];
        $invoice->bill_period = $row['bill_period'];
        $invoice->bill_date = date('Y-m-d', strtotime($row['bill_date']));
        $invoice->due_date = date('Y-m-d', strtotime($row['due_date']));
        $invoice->narration = $row['narration'];
        $invoice->arrears = $row['arrears'];
        $invoice->serial_number = $serial_number;
        $invoice->status = 1;
        $invoice->save();

        $id = $invoice->id;
        if(!empty($sae_data))
        {
            $se_serial_number = $sae_data->serial_number + 1;
        }
        else
        {
            $se_serial_number = 1;
        }
        // echo '<pre>'; print_r($to_ledgers); exit;
        $sme = array();
        foreach($to_ledgers as $k => $ledger)
        {
            $sme = new SociatyAccountEntry();
            $sme->by_ledger_id = $by_ledger->id;
            $sme->to_ledger_id = $ledger['to_ledger_id'];
            $sme->society_id = Auth::user()->society_id;
            $sme->added_user_id = Auth::user()->id;
            $sme->amount = $ledger['amount'];
            $sme->submit_date = date('Y-m-d', strtotime($row['bill_date']));
            $sme->serial_number = $se_serial_number;
            $sme->refrance_voucher_id = $id;
            $sme->voucher_type = 'invoice';
            $sme->status = 1;
            $sme->narration = $row['narration'];
            $sme->save();
        }
        return [];
    }
}
