<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PostOperativeMedicine as PostOp;
use Carbon\Carbon;
use DB;

class PostOperativeMedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
         $this->middleware('permission:postop-medicine-list|postop-medicine-create|postop-medicine-edit|postop-medicine-delete', ['only' => ['index','store']]);
         $this->middleware('permission:postop-medicine-create', ['only' => ['create','store']]);
         $this->middleware('permission:postop-medicine-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:postop-medicine-delete', ['only' => ['destroy']]);

         $this->branch = session()->get('branch');
    }

    public function index()
    {
        $postops = PostOp::leftJoin('patient_registrations as p', 'p.id', '=', 'post_operative_medicines.patient')->select('post_operative_medicines.id', 'post_operative_medicines.surgery_id', 'p.patient_name', 'p.patient_id', 'post_operative_medicines.medical_record_id')->where('type', 'postop')->where('status', 1)->get();
        return view('surgery-medicine.postop.index', compact('postops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $surgery = DB::table('surgeries')->find($id);
        $patient = DB::table('patient_registrations')->find($surgery->patient_id);
        $products = DB::table('products')->orderBy('product_name')->get();
        return view('surgery-medicine.postop.create', compact('surgery', 'patient', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['updated_by'] = Auth::user()->id;
        $input['created_by'] = Auth::user()->id;
        try{
            DB::transaction(function () use ($input, $request) {
                $surgery = PostOp::create($input);
                if(!empty($input['product'])):
                    for($i=0; $i<count($request->product); $i++):
                        $data[] = [
                            'pom_id'        => $surgery->id,
                            'product'       => $request->product[$i],
                            'batch_number'  => $request->batch_number[$i],
                            'qty'           => $request->qty[$i],
                            'dosage'        => $request->dosage[$i],
                            'price'         => $request->price[$i],
                            'discount'      => $request->discount[$i],
                            'tax'           => $request->tax[$i],
                            'tax_amount'    => $request->tax_amount[$i],
                            'total'         => $request->total[$i],
                            'created_by'    => Auth::user()->id,
                            'updated_by'    => Auth::user()->id,
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now(),
                        ];
                    endfor;
                    DB::table('post_operative_medicine_details')->insert($data);
                endif;
            });
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('postop.medicine.index')
                        ->with('success','Record updated successfully');
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
        $medicine = PostOp::find($id);
        $products = DB::table('products')->orderBy('product_name')->get();
        $medicines = DB::table('post_operative_medicine_details')->where('pom_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($medicine->patient);
        return view('surgery-medicine.postop.edit', compact('medicine', 'medicines', 'patient', 'products'));
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
        $input = $request->all();
        $medicine = PostOp::find($id);
        $input['updated_by'] = Auth::user()->id;
        $input['bill_generated'] = 1;
        $input['created_by'] = $medicine->getOriginal('created_by');
        try{
            DB::transaction(function () use ($medicine, $input, $request, $id) {
                $medicine->update($input);
                DB::table("post_operative_medicine_details")->where('pom_id', $id)->delete();
                if(!empty($input['product'])):
                    for($i=0; $i<count($request->product); $i++):
                        $data[] = [
                            'pom_id'        => $id,
                            'product'       => $request->product[$i],
                            'batch_number'  => $request->batch_number[$i],
                            'qty'           => $request->qty[$i],
                            'dosage'        => $request->dosage[$i],
                            'price'         => $request->price[$i],
                            'discount'      => $request->discount[$i],
                            'tax'           => $request->tax[$i],
                            'tax_amount'    => $request->tax_amount[$i],
                            'total'         => $request->total[$i],
                            'created_by'    => $medicine->created_by,
                            'updated_by'    => Auth::user()->id,
                            'created_at'    => $medicine->created_at,
                            'updated_at'    => Carbon::now(),
                        ];
                    endfor;
                    DB::table('post_operative_medicine_details')->insert($data);
                endif;
            });
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('postop.medicine.index')
                        ->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PostOp::find($id)->delete();
        return redirect()->route('postop.medicine.index')
                        ->with('success','Record deleted successfully');
    }
}
