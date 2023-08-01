<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\JournalVoucher;
use App\Ledger;
use App\SociatyAccountEntry;
use Session;
use App\Exports\ExportJournalVoucher;
use App\Imports\ImportJournalVoucher;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class JournalVoucherController extends Controller
{
    public function __construct()
    {        
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(\Auth::check()){
            $permissions = permission(); 
            $permissions = explode(',', $permissions);

            if(in_array(31, $permissions))
            {
                $data = array();
                $data['page_title'] = "Journal Voucher"; 
                $data['sub_title'] = "Journal Voucher List";
                // $data['journal_voucher'] = JournalVoucher::where('status', 1)->get();
                // $data['societys'] = $societys;
                $from_date = Session::get('jvfrom_date');
                $to_date = Session::get('jvto_date');
                $search = Session::get('jvsearch');

                if((isset($from_date) && !empty($from_date)) || (isset($to_date) && !empty($to_date)) || (isset($search) && !empty($search)))
                {
                    $data['journal_voucher'] = JournalVoucher::where('society_id', Auth::user()->society_id)
                                        ->where(function($q) use ($from_date, $to_date){
                                            if($from_date != '' && $to_date != '')
                                            {
                                                $q->whereBetween('submit_date', [$from_date, $to_date]);
                                            }
                                        })
                                        ->where(function($sq) use ($search)
                                        {
                                            $sq->whereHas('buyLedger', function($q) use ($search)
                                            {
                                                if($search != '')
                                                {
                                                    $q->where('name', 'LIKE', '%'.$search.'%')
                                                    ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');   
                                                }
                                            })
                                            ->orwhereHas('toLedger', function($q2) use ($search)
                                            {
                                                if($search != '')
                                                {
                                                    $q2->where('name', 'LIKE', '%'.$search.'%')
                                                    ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%'); 
                                                }
                                            });
                                        });

                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }   
                    if($limit == 'all')
                    {                     
                        $data['journal_voucher'] = $data['journal_voucher']->orderBy('submit_date', 'asc')->get();
                    }
                    else{
                        $data['journal_voucher'] = $data['journal_voucher']->orderBy('submit_date', 'asc')->paginate($limit);
                    }
                }
                else
                {
                    $limit = $request->limit;
                    if($limit == '')
                    {
                        $limit = 10;
                    }
                    if($limit == 'all')
                    {
                        $data['journal_voucher'] = JournalVoucher::where('society_id', Auth::user()->society_id)->orderBy('submit_date', 'asc')->paginate(15);
                    }
                    else{
                        $data['journal_voucher'] = JournalVoucher::where('society_id', Auth::user()->society_id)->orderBy('submit_date', 'asc')->paginate(15);
                    }
                    
                }
                return view('journal-voucher/index', $data);
            }
            else
            {
                return redirect()->back();
            }

        }else{
            return redirect('/login');
        }
    }

    public function create()
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(32, $permissions))
        {
            $data = array();
            $data['page_title'] = "Add Journal Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            return view('journal-voucher/create', $data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add(Request $request)
    {
        $payment_data = JournalVoucher::orderBy('id', 'DESC')->first('serial_number');
        $sae_data = SociatyAccountEntry::orderBy('id', 'DESC')->first('serial_number');
        $data = $request->all();
        $data['added_user_id'] = Auth::user()->id;
        $data['society_id'] = Auth::user()->society_id;
        $data['submit_date'] = date('Y-m-d', strtotime($request->submit_date));
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

        $save = JournalVoucher::create($data);

        $id = $save->id;

        $smeb = new SociatyAccountEntry();
        $smeb->by_ledger_id = $request->buy_ledger_id;
        $smeb->to_ledger_id = $request->to_ledger_id;
        $smeb->society_id = Auth::user()->society_id;
        $smeb->added_user_id = Auth::user()->id;
        $smeb->amount = $request->amount;
        $smeb->submit_date = $data['submit_date'];
        $smeb->serial_number = $serial_number;
        $smeb->refrance_voucher_id = $id;
        $smeb->voucher_type = 'journal';
        $smeb->narration = $request->narration;
        $smeb->status = $request->status;
        $smeb->save();
        return redirect()->route('journal-voucher')->with('success','Journal Voucher Successfully created.');
    }

    public function edit($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(33, $permissions))
        {
            $data = array();
            $data['page_title'] = "Edit Payment Voucher";
            $data['ledgers'] = Ledger::where('status', 1)->where('society_id', Auth::user()->society_id)->get(['id', 'name', 'wing_flat_no']);
            $data['journalv'] = JournalVoucher::where('id', $id)->first();
            return view('journal-voucher/edit', $data);
        }
        else
        {
            return redirect()->back();   
        }
    }

    public function update(Request $request, $id)
    {
        $paymentv = JournalVoucher::find($id);
        $paymentv->buy_ledger_id = $request->buy_ledger_id;
        $paymentv->to_ledger_id = $request->to_ledger_id;
        $paymentv->amount = $request->amount;
        $paymentv->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $paymentv->narration = $request->narration;
        $paymentv->status = $request->status;
        $paymentv->save();

        $smeb = SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'journal')->first();
        $smeb->by_ledger_id = $request->buy_ledger_id;
        $smeb->to_ledger_id = $request->to_ledger_id;
        $smeb->amount = $request->amount;
        $smeb->submit_date = date('Y-m-d', strtotime($request->submit_date));
        $smeb->narration = $request->narration;
        $smeb->save();

        return redirect()->route('journal-voucher')->with('success','Journal Voucher Successfully updated.');
    }

    public function delete($id)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(34, $permissions))
        {
            JournalVoucher::where('id',$id)->delete();
            SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'journal')->delete();
            return redirect()->route('journal-voucher')->with('success','Journal Voucher deleted successfully');
        }
        else
        {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $data = array();
        $data['page_title'] = "Journal Voucher"; 
        $data['sub_title'] = "Journal Voucher List";
        if($request->from_date != '')
        {
            if($request->to_date == '')
            {
                return redirect()->route('journal-voucher')->with('error','The to date field is required');
            }
        }

        if($request->from_date == '')
        {
            if($request->to_date != '')
            {
                return redirect()->route('journal-voucher')->with('error','First from date field select');
            }
        }
        $from_date = '';
        $to_date = '';
        if($request->from_date != '')
        {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if($request->to_date != '')
        {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $search = $request->search;
        Session::put('jvfrom_date', $from_date);
        Session::put('jvto_date', $to_date);
        Session::put('jvsearch', $search);
        if($from_date > $to_date)
        {
            return redirect()->route('journal-voucher')->with('error','Date is invalid please select proper date!');
        }
        $data['journal_voucher'] = JournalVoucher::where('society_id', Auth::user()->society_id)
                                    ->where(function($que) use ($from_date, $to_date){
                                        if($from_date != '' && $to_date != '')
                                        {
                                            $que->whereBetween('submit_date', [$from_date, $to_date]);
                                        }
                                    })
                                    ->where(function($sq) use ($search)
                                    {
                                        $sq->whereHas('buyLedger', function($q) use ($search)
                                        {
                                            if($search != '')
                                            {
                                                $q->where('name', 'LIKE', '%'.$search.'%')
                                                ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');  
                                            }
                                        })
                                        ->orwhereHas('toLedger', function($q2) use ($search)
                                        {
                                            if($search != '')
                                            {
                                                $q2->where('name', 'LIKE', '%'.$search.'%')
                                                ->orWhere('wing_flat_no', 'LIKE', '%'.$search.'%');   
                                            }
                                        });
                                    });
                                    $limit = $request->limit;
            if($limit == '')
            {
                $limit = 10;
            }   
            if($limit == 'all')
            {                     
                $data['journal_voucher'] = $data['journal_voucher']->orderBy('submit_date', 'asc')->get();
            }
            else{
                $data['journal_voucher'] = $data['journal_voucher']->orderBy('submit_date', 'asc')->paginate($limit);
            }
                                    // ->paginate(15);
        
        return view('journal-voucher/index', $data);
    }

    public function export(Request $request)
    {
        $permissions = permission(); 
        $permissions = explode(',', $permissions);

        if(in_array(36, $permissions))
        {
            return Excel::download(new ExportJournalVoucher, 'journal-voucher.xlsx');
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

        if(in_array(35, $permissions))
        {
            Excel::import(new ImportJournalVoucher, request()->file('file'));
            return redirect()->back()->with('success','Data Imported Successfully');
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

        if(in_array(34, $permissions))
        {
            if(!empty($request->ids))
            {
                $ids = json_decode($request->ids);
                foreach($ids as $id)
                {
                    JournalVoucher::where('id',$id)->delete();
                    SociatyAccountEntry::where('refrance_voucher_id', $id)->where('voucher_type', 'journal')->delete();
                }
                return redirect()->route('journal-voucher')->with('success','Journal Voucher deleted successfully');
            }
            else{
                return redirect()->route('journal-voucher')->with('error','Please select record');
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
        $path = $path.'/assets/demo/journal-voucher-demo.csv';
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/stream',
            'Content-Disposition' => 'inline; filename="journal-voucher-demo.csv"'
        ]);
    }
}
