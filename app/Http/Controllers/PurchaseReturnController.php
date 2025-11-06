<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseAccount;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{

    private $branch;
    function __construct()
    {
        $this->middleware('permission:purchase-return-list|purchase-return-create|purchase-return-edit|purchase-return-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:purchase-return-create', ['only' => ['create', 'fetch', 'store']]);
        $this->middleware('permission:purchase-return-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-return-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preturns = PurchaseReturn::whereDate('created_at', Carbon::today())->latest()->get();
        $inputs = array('');
        return view('pharmacy.stock.return.purchase.purchase-return', compact('preturns', 'inputs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function fetch(Request $request)
    {
        $this->validate($request, [
            'term' => 'required',
        ]);
        try {
            $purchase = Purchase::findOrFail($request->term);
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return view('pharmacy.stock.return.purchase.list', compact('purchase'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $pr = PurchaseReturn::create([
                    'supplier_id' => $request->supplier_id,
                    'notes' => $request->notes,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);
                $data = [];
                foreach ($request->product_id as $key => $item):
                    if ($request->ret_qty[$key] > 0):
                        $data[] = [
                            'return_id' => $pr->id,
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
                PurchaseReturnDetail::insert($data);
                PurchaseAccount::create([
                    'supplier_id' => $request->supplier_id,
                    'parent_id' => $pr->id,
                    'parent_type' => 'return',
                    'amount' => array_sum(array_column($data, 'ramount')),
                    'notes' => 'Purchase Return created with id: ' . $pr->id,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('purchase.return.register')->with("success", "Return recorded successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
