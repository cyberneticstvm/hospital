<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use DB;

class MedicineController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:patient-medicine-record-list|patient-medicine-record-create|patient-medicine-record-edit|patient-medicine-record-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-medicine-record-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-medicine-record-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-medicine-record-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicines = DB::table('patient_medicine_records as pmr')->leftJoin('patient_medical_records as pmr1', 'pmr.medical_record_id', '=', 'pmr1.id')->leftJoin('patient_registrations as p', 'p.id', '=', 'pmr1.patient_id')->leftJoin('doctors as doc', 'pmr1.doctor_id', '=', 'doc.id')->select('pmr.mrn', 'pmr.medical_record_id', 'pmr.status', 'p.patient_name', 'p.patient_id', 'doc.doctor_name')->groupBy('pmr.mrn', 'pmr.medical_record_id', 'pmr.status', 'p.patient_name', 'p.patient_id', 'doc.doctor_name')->orderByDesc('pmr.id')->whereDate('pmr.created_at', Carbon::today())->orderByDesc("pmr.id")->get();
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        $medical_record = DB::table('patient_medical_records')->find($id);
        $medicines = DB::table('patient_medicine_records')->where('medical_record_id', $id)->get();
        $patient = DB::table('patient_registrations')->find($medical_record->patient_id);
        $doctor = DB::table('doctors')->find($medical_record->doctor_id);
        $products = DB::table('products')->get();
        $pmodes = DB::table('payment_modes')->get();
        return view('medicine.edit', compact('medicines', 'medical_record', 'patient', 'doctor', 'products', 'pmodes'));
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
        $mrn = $input['mrn'];
        DB::table("patient_medicine_records")->where('medical_record_id', $id)->delete();
        if($input['medicine']):
            for($i=0; $i<count($input['medicine']); $i++):
                if($input['medicine'][$i] > 0):
                    $product = DB::table('products')->find($input['medicine'][$i]);
                    DB::table('patient_medicine_records')->insert([
                        'medical_record_id' => $id,
                        'mrn' => $mrn,
                        'medicine' => $input['medicine'][$i],
                        'batch_number' => $input['batch_number'][$i],
                        'dosage' => $input['dosage'][$i],
                        'notes' => $input['notes'][$i],
                        'qty' => $input['qty'][$i],
                        'price' => $input['price'][$i],
                        'discount' => $input['discount'][$i],
                        'tax_percentage' => $input['tax_percentage'][$i],
                        'tax_amount' => $input['tax_amount'][$i],
                        'total' => $input['total'][$i],
                        'status' => '1', //1-Billed, 0-Not Billed
                        'updated_by' => $request->user()->id,
                        'updated_at' => Carbon::now(),
                    ]);
                endif;
            endfor;
        endif;
        return redirect()->route('medicine.index')
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
        DB::table('patient_medicine_records')->where('medical_record_id', $id)->delete();
        return redirect()->route('medicine.index')
                        ->with('success','Record deleted successfully');
    }

    public function remove($id){
        DB::table('patient_medicine_records')->where('id', $id)->delete();
        echo "Medicine Deleted successfully.";
    }
}
