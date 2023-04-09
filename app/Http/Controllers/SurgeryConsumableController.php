<?php

namespace App\Http\Controllers;

use App\Models\doctor;
use App\Models\PatientMedicalRecord;
use App\Models\PatientRegistrations;
use App\Models\PatientSurgeryConsumable;
use App\Models\PatientSurgeryConsumableList;
use App\Models\SurgeryConsumable;
use App\Models\SurgeryConsumableItem;
use App\Models\SurgeryType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Exception;

class SurgeryConsumableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:surgery-consumable-list|surgery-consumable-create|surgery-consumable-edit|surgery-consumable-delete|surgery-consumable-surgery-list|surgery-consumable-surgery-create|surgery-consumable-surgery-edit|surgery-consumable-surgery-delete|patient-surgery-consumable-list|patient-surgery-consumable-create|patient-surgery-consumable-edit|patient-surgery-consumable-delete', ['only' => ['index','store']]);
         $this->middleware('permission:surgery-consumable-create', ['only' => ['create','store']]);
         $this->middleware('permission:surgery-consumable-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:surgery-consumable-delete', ['only' => ['destroy']]);
         $this->middleware('permission:surgery-consumable-surgery-list', ['only' => ['showsurgeryconsumable']]);
         $this->middleware('permission:surgery-consumable-surgery-create', ['only' => ['savesurgeryconsumable']]);
         $this->middleware('permission:surgery-consumable-surgery-edit', ['only' => ['editsurgeryconsumable', 'updatesurgeryconsumable']]);
         $this->middleware('permission:surgery-consumable-surgery-delete', ['only' => ['deletesurgeryconsumable']]);
         $this->middleware('permission:patient-surgery-consumable-list', ['only' => ['patientsurgeryconsumablelist']]);
         $this->middleware('permission:patient-surgery-consumable-create', ['only' => ['patientsurgeryconsumablefetch', 'patientsurgeryconsumablecreate', 'patientsurgeryconsumablelistshow', 'patientsurgeryconsumablelistsave']]);
         $this->middleware('permission:patient-surgery-consumable-edit', ['only' => ['patientsurgeryconsumablelistedit', 'patientsurgeryconsumablelistupdate']]);
         $this->middleware('permission:patient-surgery-consumable-delete', ['only' => ['patientsurgeryconsumablelistdelete']]);
         
    }

    public function index()
    {
        $scs = SurgeryConsumable::all();
        return view('surgery-consumables.index', compact('scs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('surgery-consumables.create');
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
            'name' => 'required|unique:surgery_consumables,name',
            'price' => 'required',
        ]);
        $input = $request->all();
        SurgeryConsumable::create($input);
        return redirect()->route('surgery.consumable.index')->with('success','Product created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sc = SurgeryConsumable::find($id);
        return view('surgery-consumables.edit', compact('sc'));
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
            'name' => 'required|unique:surgery_consumables,name,'.$id,
            'price' => 'required',
        ]);
        $input = $request->all();
        $sc = SurgeryConsumable::find($id);
        $sc->update($input);
        return redirect()->route('surgery.consumable.index')->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SurgeryConsumable::find($id)->delete();
        return redirect()->route('surgery.consumable.index')->with('success','Product deleted successfully');
    }

    public function showsurgeryconsumable(){
        $surgeries = SurgeryType::all();
        $consumables = SurgeryConsumable::all();
        $scis = SurgeryConsumableItem::all();
        return view('surgery-consumables.surgery-consumable-list', compact('scis', 'surgeries', 'consumables'));
    }

    public function savesurgeryconsumable(Request $request){
        $this->validate($request, [
            'surgery_id' => 'required',
            'consumable_id' => 'required',
            'default_qty' => 'required',
        ]);
        $input = $request->all();
        SurgeryConsumableItem::create($input);
        return redirect()->route('surgery.consumable.surgey')->with('success','Record saved successfully');
    }

    public function editsurgeryconsumable($id){
        $surgeries = SurgeryType::all();
        $consumables = SurgeryConsumable::all();
        $sc = SurgeryConsumableItem::find($id);
        return view('surgery-consumables.edit-surgery-consumable-list', compact('sc', 'surgeries', 'consumables'));
    }

    public function updatesurgeryconsumable(Request $request, $id){
        $this->validate($request, [
            'surgery_id' => 'required',
            'consumable_id' => 'required',
            'default_qty' => 'required',
        ]);
        $input = $request->all();
        $sci = SurgeryConsumableItem::find($id);
        $sci->update($input);
        return redirect()->route('surgery.consumable.surgey')->with('success','Record updated successfully');
    }

    public function deletesurgeryconsumable($id)
    {
        SurgeryConsumableItem::find($id)->delete();
        return redirect()->route('surgery.consumable.surgey')->with('success','Record deleted successfully');
    }

    public function patientsurgeryconsumablelist(){
        $pscls = PatientSurgeryConsumable::orderByDesc('id')->get();
        return view('surgery-consumables.patient-surgery-consumable-list', compact('pscls'));
    }

    public function patientsurgeryconsumablecreate(){
        return view('surgery-consumables.patient-surgery-consumable-create');
    }

    public function patientsurgeryconsumablefetch(Request $request){
        $this->validate($request, [
            'medical_record_number' => 'required',
        ]);
        $mrecord = PatientMedicalRecord::find($request->medical_record_number);        
        if($mrecord):
            $patient = PatientRegistrations::find($mrecord->patient_id);
            $doctor = doctor::find($mrecord->doctor_id);
            $stypes = SurgeryType::all();
            $consumables = SurgeryConsumable::all();
            return view('surgery-consumables.patient-surgery-consumable-show', compact('mrecord', 'patient', 'doctor', 'stypes', 'consumables'));
        else:
            return redirect("/patient/surgery/consumable/create")->withErrors('No records found.');
        endif;
    }

    public function patientsurgeryconsumablelistsave(Request $request){
        $this->validate($request, [
            'medical_record_id' => 'required',
            'patient_id' => 'required',
            'branch' => 'required',
            'surgery_id' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;

        $input['bill_number'] = PatientSurgeryConsumable::latest()->first()->bill_number + 1;
        $tot = 0;
        foreach($request->consumable_id as $key => $val):
            $item = SurgeryConsumable::find($val);
            $tot += $item->price*$request->qty[$key];
        endforeach;
        $input['total'] = $tot;
        $input['total_after_discount'] = $tot - $request->discount;
        try{
            DB::transaction(function() use ($request, $input) {
                $psc = PatientSurgeryConsumable::create($input);
                foreach($request->consumable_id as $key => $val):
                    $item = SurgeryConsumable::find($val);
                    $data [] = [
                        'psc_id' => $psc->id,
                        'consumable_id' => $val,
                        'cost' => $item->price,
                        'qty' => $request->qty[$key],
                        'total' => $item->price*$request->qty[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                endforeach;
                PatientSurgeryConsumableList::insert($data);
            });            
        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('patient.surgey.consumable')->with('success', 'Record updated successfully');
    }

    public function patientsurgeryconsumablelistedit($id){
        $psc = PatientSurgeryConsumable::find($id);
        $stypes = SurgeryType::all();
        $consumables = SurgeryConsumable::all();
        return view('surgery-consumables.patient-surgery-consumable-edit', compact('psc', 'stypes', 'consumables'));
    }

    public function patientsurgeryconsumablelistupdate(Request $request, $id){
        $this->validate($request, [
            'medical_record_id' => 'required',
            'patient_id' => 'required',
            'branch' => 'required',
            'surgery_id' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        
        $tot = 0;
        foreach($request->consumable_id as $key => $val):
            $item = SurgeryConsumable::find($val);
            $tot += $item->price*$request->qty[$key];
        endforeach;
        $input['total'] = $tot;
        $input['total_after_discount'] = $tot - $request->discount;

        try{
            DB::transaction(function() use ($request, $input, $id) {
                $psc = PatientSurgeryConsumable::find($id);
                $psc->update($input);
                foreach($request->consumable_id as $key => $val):
                    $item = SurgeryConsumable::find($val);
                    $data [] = [
                        'psc_id' => $psc->id,
                        'consumable_id' => $val,
                        'cost' => $item->price,
                        'qty' => $request->qty[$key],
                        'total' => $item->price*$request->qty[$key],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                endforeach;
                PatientSurgeryConsumableList::where('psc_id', $id)->delete();
                PatientSurgeryConsumableList::insert($data);
            });            
        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('patient.surgey.consumable')->with('success', 'Record updated successfully');
    }

    public function patientsurgeryconsumablelistdelete($id){
        PatientSurgeryConsumable::find($id)->delete();
        return redirect()->route('patient.surgey.consumable')->with('success', 'Record deleted successfully');
    }
}
