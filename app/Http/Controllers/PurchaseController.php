<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Purchase;
use Carbon\Carbon;
use DB;

class PurchaseController extends Controller
{
    private $branch;
    
    function __construct()
    {
         $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['index','store']]);
         $this->middleware('permission:purchase-create', ['only' => ['create','store']]);
         $this->middleware('permission:purchase-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::leftJoin('suppliers as s', 'purchases.supplier', '=', 's.id')->select('purchases.id', 'purchases.invoice_number', 'purchases.order_date', 'purchases.delivery_date', 's.name')->orderBy('purchases.created_at','DESC')->get();
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('products')->get();
        $suppliers = DB::table('suppliers')->get();
        return view('purchase.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
            'supplier' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);
        $input = $request->all();
        $input['order_date'] = (!empty($request->order_date)) ? Carbon::createFromFormat('d/M/Y', $input['order_date'])->format('Y-m-d') : NULL;
        $input['delivery_date'] = (!empty($request->delivery_date)) ? Carbon::createFromFormat('d/M/Y', $input['delivery_date'])->format('Y-m-d') : NULL;
        $input['created_by'] = $request->user()->id;
        try{
            DB::transaction(function() use ($input) {
                $purchase = Purchase::create($input);
                if($input['product']):
                    for($i=0; $i<count($input['product']); $i++):
                        if($input['product'][$i] > 0):
                            $tot = ($input['purchase_price'][$i] > 0 ) ? $input['qty'][$i]*$input['purchase_price'][$i] : 0.00;
                            $tot = $tot+$input['adjustment'][$i];
                            $data[] = [
                                'purchase_id' => $purchase->id,
                                'product' => $input['product'][$i],
                                'batch_number' => $input['batch_number'][$i],
                                'purchase_type' => ($input['purchase_price'][$i] > 0) ? 'paid' : 'free',
                                'expiry_date' => $input['expiry_date'][$i],
                                'qty' => $input['qty'][$i],
                                'purchase_price' => $input['purchase_price'][$i],
                                'mrp' => $input['mrp'][$i],
                                'price' => $input['price'][$i],
                                'adjustment' => $input['adjustment'][$i],
                                'total' => $tot,
                                'created_at' => $purchase->created_at,
                                'updated_at' => $purchase->updated_at,
                            ];
                        endif;
                    endfor;
                    DB::table('purchase_details')->insert($data);            
                endif;
            });
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('purchase.index')->with('success','Purchase recorded successfully');
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
        $products = DB::table('products')->get();
        $suppliers = DB::table('suppliers')->get();
        $purchase = Purchase::find($id);
        $purchase_details = DB::table('purchase_details')->where('purchase_id', '=', $id)->get();
        return view('purchase.edit', compact('products', 'suppliers', 'purchase', 'purchase_details'));
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
        $this->validate($request, [
            'product' => 'required',
            'supplier' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);
        $input = $request->all();
        $input['order_date'] = (!empty($request->order_date)) ? Carbon::createFromFormat('d/M/Y', $input['order_date'])->format('Y-m-d') : NULL;
        $input['delivery_date'] = (!empty($request->delivery_date)) ? Carbon::createFromFormat('d/M/Y', $input['delivery_date'])->format('Y-m-d') : NULL;
        $purchase = Purchase::find($id);
        $input['created_by'] = $purchase->getOriginal('created_by');
        DB::transaction(function() use ($input, $purchase, $id) {
            $purchase->update($input);
            DB::table("purchase_details")->where('purchase_id', $purchase->id)->delete();
            if($input['product']):
                for($i=0; $i<count($input['product']); $i++):
                    if($input['product'][$i] > 0):
                        $tot = ($input['purchase_price'][$i] > 0 ) ? $input['qty'][$i]*$input['purchase_price'][$i] : 0.00;
                        $tot = $tot+$input['adjustment'][$i];
                        $data[] = [
                            'purchase_id' => $purchase->id,
                            'product' => $input['product'][$i],
                            'batch_number' => $input['batch_number'][$i],
                            'expiry_date' => $input['expiry_date'][$i],
                            'purchase_type' => ($input['purchase_price'][$i] > 0) ? 'paid' : 'free',
                            'qty' => $input['qty'][$i],
                            'purchase_price' => $input['purchase_price'][$i],
                            'mrp' => $input['mrp'][$i],
                            'price' => $input['price'][$i],
                            'adjustment' => $input['adjustment'][$i],
                            'total' => $tot,
                            'created_at' => $purchase->created_at,
                            'updated_at' => Carbon::now(),
                        ];
                    endif;
                endfor;
                DB::table('purchase_details')->insert($data);
            endif;
        });
        
        return redirect()->route('purchase.index')->with('success','Purchase record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Purchase::find($id)->delete();
        return redirect()->route('purchase.index')
                        ->with('success','Purchase record deleted successfully');
    }
}
