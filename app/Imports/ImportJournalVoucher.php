<?php

namespace App\Imports;

use App\JournalVoucher;
use App\SociatyAccountEntry;
use App\Ledger;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Auth;
use App\Rules\LedgerNameRule;

class ImportJournalVoucher implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return[
            'by_ledger' => [
                'required', new LedgerNameRule('journalvoucher', 'byledger')
            ],
            'to_ledger' => [
                'required', new LedgerNameRule('journalvoucher', 'toledger')
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
        }
        else{
            $name = preg_replace('/(\s?\&\s?)/', "&", $tonames[0]);
            $name = str_replace('&',',',$name);
            $to_ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->first();
        }

        $journal_data = JournalVoucher::orderBy('id', 'DESC')->first('serial_number');
        if(!empty($journal_data))
        {
            $serial_number = $journal_data->serial_number + 1;
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

        $journal_voucher = new JournalVoucher();
        $journal_voucher->buy_ledger_id = $by_ledger->id;
        $journal_voucher->to_ledger_id = $to_ledger->id;
        $journal_voucher->society_id = Auth::user()->society_id;
        $journal_voucher->added_user_id = Auth::user()->id;
        $journal_voucher->amount = $row['amount'];
        $journal_voucher->submit_date = date('Y-m-d', strtotime($row['submit_date']));
        $journal_voucher->narration = $row['narration'];
        $journal_voucher->serial_number = $serial_number;
        $journal_voucher->save();

        return new SociatyAccountEntry([
            'by_ledger_id' => $by_ledger->id,
            'to_ledger_id' => $to_ledger->id,
            'society_id' => Auth::user()->society_id,
            'added_user_id' => Auth::user()->id,   
            'amount' => $row['amount'],
            'submit_date' => date('Y-m-d', strtotime($row['submit_date'])),
            'serial_number' => $serial_number_sae,
            'refrance_voucher_id' => $journal_voucher->id,
            'voucher_type' => 'journal',
            'status' => 1,
            'narration' => $row['narration'],
        ]);
    }
}
