<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Carbon\Carbon;
use DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = DB::table('purchases as p')->leftJoin('products as pr', 'p.product', '=', 'pr.id')->leftJoin('suppliers as s', 'p.supplier', '=', 's.id')->select('p.*', 'pr.product_name', 's.name as supplier_name')->orderBy('p.created_at','DESC')->get();
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
        $input['order_date'] = (!empty($request->order_date)) ? Carbon::createFromFormat('d/M/Y', $request['order_date'])->format('Y-m-d') : NULL;
        $input['delivery_date'] = (!empty($request->delivery_date)) ? Carbon::createFromFormat('d/M/Y', $request['delivery_date'])->format('Y-m-d') : NULL;
        $input['created_by'] = $request->user()->id;
        $input['total'] = $input['qty']*$input['price'];
        $purchase = Purchase::create($input);
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
        return view('purchase.edit', compact('products', 'suppliers', 'purchase'));
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
        $input['order_date'] = (!empty($request->order_date)) ? Carbon::createFromFormat('d/M/Y', $request['order_date'])->format('Y-m-d') : NULL;
        $input['delivery_date'] = (!empty($request->delivery_date)) ? Carbon::createFromFormat('d/M/Y', $request['delivery_date'])->format('Y-m-d') : NULL;
        $purchase = Purchase::find($id);
        $input['created_by'] = $purchase->getOriginal('created_by');
        $input['total'] = $input['qty']*$input['price'];
        $purchase->update($input);
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
