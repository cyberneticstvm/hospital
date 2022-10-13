<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ProductTransfer;
use Carbon\Carbon;
use DB;
use App\Helper\Helper;

class ProductTransferController extends Controller
{
    private $branch;

    function __construct()
    {
         $this->middleware('permission:product-transfer-list|product-transfer-create|product-transfer-edit|product-transfer-delete', ['only' => ['index','store']]);
         $this->middleware('permission:product-transfer-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-transfer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-transfer-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = DB::table('product_transfers AS t')->leftJoin('branches AS b', 't.from_branch', '=', 'b.id')->leftJoin('branches AS b1', 't.to_branch', '=', 'b1.id')->where('t.from_branch', $this->branch)->select('t.id', 't.transfer_note AS tnote', DB::raw("IFNULL('Main Stock', b.branch_name) AS from_branch"), 'b1.branch_name AS to_branch', 't.transfer_date AS tdate')->orderBy('t.transfer_date','DESC')->get();
        return view('product-transfer.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('products')->get();
        $branches = DB::table('branches')->get();
        return view('product-transfer.create', compact('products', 'branches'));
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
            'from_branch' => 'required',
            'to_branch' => 'required',
            'transfer_date' => 'required',
        ]);
        $input = $request->all();
        $input['transfer_date'] = (!empty($request->transfer_date)) ? Carbon::createFromFormat('d/M/Y', $request['transfer_date'])->format('Y-m-d') : NULL;        
        $input['created_by'] = $request->user()->id;
        $transfer = ProductTransfer::create($input);
        if($input['product']):
            for($i=0; $i<count($input['product']); $i++):
                if($input['product'][$i] > 0):
                    DB::table('product_transfer_details')->insert([
                        'transfer_id' => $transfer->id,
                        'product' => $input['product'][$i],
                        'batch_number' => $input['batch_number'][$i],
                        'qty' => $input['qty'][$i],
                    ]);
                endif;
            endfor;
        endif;
        return redirect()->route('product-transfer.index')->with('success','Product Transferred successfully');
        /*$available_qty = Helper::getAvailableStock($request->product, $request->batch_number, $request->from_branch);
        if($available_qty >= $request->qty):
            $transfer = ProductTransfer::create($input);
            return redirect()->route('product-transfer.index')->with('success','Product Transferred successfully');
        else:
            return redirect("/product-transfer/create/")->withErrors('Insufficient Quantity');
        endif;*/
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
        $transfer = ProductTransfer::find($id);
        $branches = DB::table('branches')->get();
        $products = DB::table('products')->get();
        $transfer_details = DB::table('product_transfer_details')->get();
        return view('product-transfer.edit', compact('transfer','branches', 'products', 'transfer_details'));
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
            'from_branch' => 'required',
            'to_branch' => 'required',
            'transfer_date' => 'required',
        ]);
        $input = $request->all();
        $input['transfer_date'] = (!empty($request->transfer_date)) ? Carbon::createFromFormat('d/M/Y', $request['transfer_date'])->format('Y-m-d') : NULL;

        $transfer = ProductTransfer::find($id);
        $input['created_by'] = $transfer->getOriginal('created_by');
        $transfer->update($input);

        DB::table("product_transfer_details")->where('transfer_id', $id)->delete();

        if($input['product']):
            for($i=0; $i<count($input['product']); $i++):
                if($input['product'][$i] > 0):
                    DB::table('product_transfer_details')->insert([
                        'transfer_id' => $transfer->id,
                        'product' => $input['product'][$i],
                        'batch_number' => $input['batch_number'][$i],
                        'qty' => $input['qty'][$i],
                    ]);
                endif;
            endfor;
        endif;
        return redirect()->route('product-transfer.index')->with('success','Product Transfer Updated successfully');
        /*$available_qty = Helper::getAvailableStock($request->product, $request->batch_number, $request->from_branch);
        if($available_qty >= $request->qty):
            $transfer = ProductTransfer::create($input);
            return redirect()->route('product-transfer.index')->with('success','Product Transfer Updated successfully');
        else:
            return redirect("/product-transfer/edit/".$id)->withErrors('Insufficient Quantity');
        endif;*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductTransfer::find($id)->delete();
        return redirect()->route('product-transfer.index')
                        ->with('success','Record deleted successfully');
    }
}
