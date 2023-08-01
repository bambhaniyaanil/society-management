<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Ledger;
use Auth;

class LedgerNameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $ledger)
    {
        $this->type = $type;
        $this->ledger = $ledger;
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->type == 'ledger')
        {        
            $data = explode(' - ', $value);         
            if(count($data) == 2)
            {
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[1]);
                $name = str_replace('&',',',$name);
                $ledger = Ledger::where('name', $name)->where('wing_flat_no', $data[0])->where('society_id', Auth::user()->society_id)->count();
            }
            else{
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[0]);
                $name = str_replace('&',',',$name);
                $ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->count();
            }
            if($ledger == 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif($this->type == 'invoice')
        {
            if($this->ledger == 'toledger')
            {
                $datas = explode(',', $value);
                foreach($datas as $rec)
                {
                    $data1 = explode('=', $rec); 
                    $data = explode(' - ', $data1[0]);
                    if(count($data) == 2)
                    {
                        $name = preg_replace('/(\s?\&\s?)/', "&", $data[1]);
                        $name = str_replace('&',',',$name);
                        $ledger = Ledger::where('name', $name)->where('wing_flat_no', $data[0])->where('society_id', Auth::user()->society_id)->count();
                    }
                    else
                    {
                        $name = preg_replace('/(\s?\&\s?)/', "&", $data[0]);
                        $name = str_replace('&',',',$name);
                        $ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->count();
                    }
                    if($ledger == 0)
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
            }
            else
            {
                $data = explode(' - ', $value);
                if(count($data) == 2)
                {
                    $name = preg_replace('/(\s?\&\s?)/', "&", $data[1]);
                    $name = str_replace('&',',',$name);
                    $ledger = Ledger::where('name', $name)->where('wing_flat_no', $data[0])->where('society_id', Auth::user()->society_id)->count();
                }
                else{
                    $name = preg_replace('/(\s?\&\s?)/', "&", $data[0]);
                    $name = str_replace('&',',',$name);
                    $ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->count();
                }
                if($ledger == 0)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
        else{
            $data = explode(' - ', $value);
            if(count($data) == 2)
            {
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[1]);
                $name = str_replace('&',',',$name);
                $ledger = Ledger::where('name', $name)->where('wing_flat_no', $data[0])->where('society_id', Auth::user()->society_id)->count();
            }
            else{
                $name = preg_replace('/(\s?\&\s?)/', "&", $data[0]);
                $name = str_replace('&',',',$name);
                $ledger = Ledger::where('name', $name)->where('society_id', Auth::user()->society_id)->count();
            }
            if($ledger == 0)
            {
                // echo $data[0];
                // echo $name; exit;
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->type == 'ledger')
        {
            return 'Ledger allready exit';
        }        

        if($this->ledger == 'byledger')
        {
            return 'By ledger name is not found';
        }
        if($this->ledger == 'toledger')
        {
            return 'To ledger name is not found';
        }
    }
}
