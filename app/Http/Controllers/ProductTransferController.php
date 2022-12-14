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
         $this->middleware('permission:stock-in-hand', ['only' => ['fetch']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = DB::table('product_transfers AS t')->leftJoin('branches AS b', 't.from_branch', '=', 'b.id')->leftJoin('branches AS b1', 't.to_branch', '=', 'b1.id')->where('t.from_branch', $this->branch)->select('t.id', 't.transfer_note AS tnote', DB::raw("CASE WHEN t.from_branch = 0 THEN 'Main Stock' ELSE b.branch_name END AS from_branch"), DB::raw("CASE WHEN t.to_branch = 0 THEN 'Main Stock' ELSE b1.branch_name END AS to_branch"), 't.transfer_date AS tdate')->orderBy('t.transfer_date','DESC')->get();
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
    public function show()
    {
        $branches = DB::table('branches')->get();
        $products = DB::table('products')->orderBy('product_name')->get();
        $inventory = []; $input = [];
        return view('product-transfer.stock-in-hand', compact('branches', 'products', 'inventory', 'input'));
    }

    public function fetch(Request $request){
        $this->validate($request, [
            'branch' => 'required',
            'product' => 'required',
        ]);
        $input = $request->all();
        $branches = DB::table('branches')->get();
        $products = DB::table('products')->orderBy('product_name')->get();
        $input = array($request->branch, $request->product);
        $product = $request->product;
        if($request->branch == 0):
            $inventory = DB::select("SELECT 'Main Branch' AS branch, tbl1.product, tbl1.product_name, tbl1.batch_number AS batch_number, tbl1.purchased AS purchased, SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS transferred, tbl1.purchased-SUM(CASE WHEN tbl1.batch_number = t.batch_number THEN t.qty ELSE 0 END) AS balance_qty FROM (SELECT p.id, pr.product_name, p.product, SUM(p.qty) AS purchased, p.batch_number FROM purchase_details p LEFT JOIN products pr ON p.product=pr.id WHERE IF($product > 0, p.product = ?, 1) GROUP BY p.batch_number) AS tbl1 LEFT JOIN product_transfer_details t ON tbl1.product = t.product LEFT JOIN product_transfers pt ON pt.id=t.transfer_id GROUP BY tbl1.id HAVING balance_qty > 0 ORDER BY tbl1.product_name", [$request->product]);
        else:
            $branch = DB::table('branches')->where('id', $request->branch)->value('branch_name');
            $inventory = DB::select("SELECT '$branch' AS branch, tbl4.received AS purchased, tbl4.transferred+tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty AS transferred, tbl4.product_name, tbl4.product, tbl4.batch_number, tbl4.balance-(tbl4.medqty+tbl4.pharmaqty+tbl4.surgery_qty) AS balance_qty FROM (SELECT tbl3.*, IFNULL(SUM(CASE WHEN tbl3.batch_number = po.batch_number AND pm.branch = ? AND pm.bill_generated = 1 THEN po.qty END), 0) AS surgery_qty FROM (SELECT tbl2.*, IFNULL(SUM(CASE WHEN tbl2.batch_number = p.batch_number AND ph.branch = ? THEN p.qty END), 0) AS pharmaqty FROM (SELECT tbl1.*, IFNULL(SUM(CASE WHEN tbl1.batch_number = m.batch_number AND pmr.branch = ? AND m.status = 1 THEN m.qty END), 0) AS medqty FROM (SELECT tblTrnsf.*, tblTrnsf.received-tblTrnsf.transferred AS balance FROM (SELECT pr.product_name, pd.product, pd.batch_number, IFNULL(SUM(CASE WHEN pt.to_branch = ? THEN pd.qty END), 0) AS received, IFNULL(SUM(CASE WHEN pt.from_branch = ? THEN pd.qty END), 0) AS transferred FROM product_transfer_details pd LEFT JOIN product_transfers pt ON pd.transfer_id = pt.id LEFT JOIN products pr ON pr.id = pd.product GROUP BY pd.batch_number) AS tblTrnsf) AS tbl1 LEFT JOIN patient_medicine_records m ON m.medicine = tbl1.product LEFT JOIN patient_medical_records pmr ON pmr.id = m.medical_record_id GROUP BY tbl1.batch_number) AS tbl2 LEFT JOIN pharmacy_records p ON p.product = tbl2.product LEFT JOIN pharmacies ph ON ph.id = p.pharmacy_id GROUP BY tbl2.batch_number) AS tbl3 LEFT JOIN post_operative_medicine_details po ON po.product = tbl3.product LEFT JOIN post_operative_medicines pm ON pm.id = po.pom_id GROUP BY tbl3.batch_number) AS tbl4 WHERE IF($product > 0, tbl4.product = ?, 1) HAVING balance_qty > 0", [$request->branch, $request->branch, $request->branch, $request->branch, $request->branch, $request->product]);
        endif;
        return view('product-transfer.stock-in-hand', compact('branches', 'products', 'inventory', 'input'));
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
