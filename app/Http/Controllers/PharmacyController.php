<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Carbon\Carbon;
use DB;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()    {
         $this->middleware('permission:pharmacy-list|pharmacy-create|pharmacy-edit|pharmacy-delete', ['only' => ['index','store']]);
         $this->middleware('permission:pharmacy-create', ['only' => ['create','store']]);
         $this->middleware('permission:pharmacy-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:pharmacy-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    public function index()
    {
        $records = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'pr.pharmacy_id', '=', 'p.id')->where('p.branch', $this->branch)->select('p.id', 'p.patient_name', 'p.other_info', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->groupBy('p.id')->orderByDesc('p.id')->get();
        return view('pharmacy.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('products')->get();
        return view('pharmacy.create', compact('products'));
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
            'patient_name' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $input['updated_by'] = Auth::user()->id;
        $input['branch'] = $this->branch;
        try{
            $pharmacy = Pharmacy::create($input);
            if(!empty($input['product'])):
                for($i=0; $i<count($request->product); $i++):
                    $product = DB::table('products')->where('id', $request->product[$i])->first();
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$i],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$i],
                        'qty'           => $request->qty[$i],
                        'dosage'        => $request->dosage[$i],
                        'price'         => $request->price[$i],
                        'discount'      => $request->discount[$i],
                        'tax'           => $request->tax[$i],
                        'tax_amount'    => $request->tax_amount[$i],
                        'total'         => $request->total[$i],
                    ];
                endfor;
                DB::table('pharmacy_records')->insert($data);
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('pharmacy.index')
                        ->with('success','Record added successfully');
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
        $pharmacy = Pharmacy::find($id);
        $records = DB::table('pharmacy_records')->where('pharmacy_id', $id)->get();
        return view('pharmacy.edit', compact('products', 'pharmacy', 'records'));
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
            'patient_name' => 'required',
        ]);
        $input = $request->all();
        $pharmacy = Pharmacy::find($id);
        $input['updated_by'] = Auth::user()->id;
        $input['created_by'] = $pharmacy->getOriginal('created_by');
        $input['branch'] = $this->branch;
        try{
            $pharmacy->update($input);
            DB::table("pharmacy_records")->where('pharmacy_id', $id)->delete();
            if(!empty($input['product'])):
                for($i=0; $i<count($request->product); $i++):
                    $product = DB::table('products')->where('id', $request->product[$i])->first();
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$i],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$i],
                        'qty'           => $request->qty[$i],
                        'dosage'        => $request->dosage[$i],
                        'price'         => $request->price[$i],
                        'discount'      => $request->discount[$i],
                        'tax'           => $request->tax[$i],
                        'tax_amount'    => $request->tax_amount[$i],
                        'total'         => $request->total[$i],
                    ];
                endfor;
                DB::table('pharmacy_records')->insert($data);
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('pharmacy.index')
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
        Pharmacy::find($id)->delete();
        return redirect()->route('pharmacy.index')
                        ->with('success','Record deleted successfully');
    }
}
