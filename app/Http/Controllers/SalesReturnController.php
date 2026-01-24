<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicineRecord;
use App\Models\Pharmacy;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    private $branch;
    function __construct()
    {
        $this->middleware('permission:sales-return-list|sales-return-create|sales-return-edit|sales-return-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:sales-return-create', ['only' => ['create', 'fetch', 'store']]);
        $this->middleware('permission:sales-return-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sales-return-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    function index()
    {
        $sreturns = SalesReturn::whereDate('created_at', Carbon::today())->latest()->get();
        $inputs = array('', 'medicine');
        return view('pharmacy.stock.return.sales.sales-return', compact('sreturns', 'inputs'));
    }

    function fetch(Request $request)
    {
        $this->validate($request, [
            'term' => 'required',
            'source' => 'required',
        ]);
        try {
            $source = $request->source;
            $term = $request->term;
            if ($request->source == 'Medicine'):
                $sales = PatientMedicineRecord::where('medical_record_id', $request->term)->whereNull('deleted_at')->whereNull('stock_updated_at')->where('status', 1)->select('medicine AS product', 'batch_number', 'qty', 'total', 'branch_id')->get();
            else:
                $data = Pharmacy::where('medical_record_id', $request->term)->orWhere('id', $request->term)->whereNull('deleted_at')->whereNull('stock_updated_at')->first();
                $bid = $data->branch;
                $sales = DB::table('pharmacy_records')->where('pharmacy_id', $data->id)->select('product', 'batch_number', 'qty', 'total', "$bid AS branch_id")->get();
            endif;
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return view('pharmacy.stock.return.sales.list', compact('sales', 'source', 'term'));
    }

    function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $sr = SalesReturn::create([
                    'pharmacy_id' => $request->term,
                    'source' => $request->source,
                    'branch_id' => $request->branch_id,
                    'notes' => $request->notes,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);
                $data = [];
                foreach ($request->product_id as $key => $item):
                    if ($request->ret_qty[$key] > 0):
                        $data[] = [
                            'return_id' => $sr->id,
                            'product_id' => $item,
                            'batch_number' => $request->batch_number[$key],
                            'oqty' => $request->qty[$key],
                            'rqty' => $request->ret_qty[$key],
                            'ramount' => ($request->total[$key] / $request->qty[$key]) * $request->ret_qty[$key],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    endif;
                endforeach;
                SalesReturnDetail::insert($data);
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('sales.return.register')->with("success", "Return recorded successfully");
    }
}
