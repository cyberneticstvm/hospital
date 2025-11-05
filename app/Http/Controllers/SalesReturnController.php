<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicineRecord;
use App\Models\Pharmacy;
use App\Models\SalesReturn;
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
        $sreturns = SalesReturn::latest()->get();
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
            if ($request->source == 'Medicine'):
                $sales = PatientMedicineRecord::where('medical_record_id', $request->term)->whereNull('deleted_at')->whereNull('stock_updated_at')->where('status', 1)->select('medicine AS product', 'batch_number', 'qty', 'total')->get();
            else:
                $data = Pharmacy::where('medical_record_id', $request->term)->orWhere('id', $request->term)->whereNull('deleted_at')->whereNull('stock_updated_at')->where('status', 1)->first();
                $sales = DB::table('pharmacy_records')->where('pharmacy_id', $data->id)->select('product', 'batch_number', 'qty', 'total')->get();
            endif;
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return view('pharmacy.stock.return.sales.list', compact('sales', 'source'));
    }
}
