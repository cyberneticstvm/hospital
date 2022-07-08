<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\LabRadiology;
use Carbon\Carbon;
use DB;

class LabRadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct(){
        $this->middleware('permission:lab-radiology-list|lab-radiology-create|lab-radiology-edit|lab-radiology-delete', ['only' => ['index','store']]);
        $this->middleware('permission:lab-radiology-create', ['only' => ['create','store', 'fetch']]);
        $this->middleware('permission:lab-radiology-edit', ['only' => ['edit','update', 'fetch']]);
        $this->middleware('permission:lab-radiology-delete', ['only' => ['destroy']]);
   }

    public function index()
    {
        $labs = DB::table('lab_radiologies')->leftJoin('patient_medical_records AS m', 'lab_radiologies.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('doctors AS d', 'm.doctor_id', '=', 'd.id')->selectRaw("lab_radiologies.id, lab_radiologies.medical_record_id, p.patient_name, p.patient_id, d.doctor_name, DATE_FORMAT(lab_radiologies.created_at, '%d/%b/%Y') AS ldate")->groupBy('lab_radiologies.medical_record_id')->get();
        return view('lab.radiology.index', compact('labs'));
    }

    public function fetch(){
        $lab_record = [];
        return view('lab.radiology.fetch', compact('lab_record'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $bno = DB::table('lab_radiologies')->selectRaw("IFNULL(max(bill_number)+1, 1) AS bill_number")->first();
        try{
            if($input['test_id']):
                for($i=0; $i<count($input['test_id']); $i++):
                    if($input['test_id'][$i] > 0):
                        DB::table('lab_radiologies')->insert([
                            'medical_record_id' => $request->medical_record_id,
                            'bill_number' => $bno->bill_number,
                            'lab_type_id' => $input['test_id'][$i],
                            'tested_from' => $input['tested_from'][$i],
                            'notes' => $input['notes'][$i],
                            'created_by' => $request->user()->id,
                            'updated_by' => $request->user()->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    endif;
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('lab.radiology.index')->with('success','Lab Record created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'medical_record_number' => 'required',
        ]);
        $mrecord = DB::table('patient_medical_records')->find($request->medical_record_number);
        $labtests = DB::table('lab_types')->where('category_id', 2)->get();
        if($mrecord):
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('lab.radiology.create', compact('mrecord', 'patient', 'doctor', 'age', 'labtests'));
        else:
            return redirect("/lab/radiology/fetch/")->withErrors('No records found.');
        endif;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lab_records = LabRadiology::where('medical_record_id', $id)->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $labtests = DB::table('lab_types')->where('category_id', 2)->get();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('lab.radiology.edit', compact('lab_records', 'patient', 'mrecord', 'labtests', 'doctor', 'age'));
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
        $bno = LabRadiology::where('medical_record_id', $id)->select('bill_number')->first();
        LabRadiology::where('medical_record_id', $id)->delete();
        try{
            if($input['test_id']):
                for($i=0; $i<count($input['test_id']); $i++):
                    if($input['test_id'][$i] > 0):
                        DB::table('lab_radiologies')->insert([
                            'medical_record_id' => $request->medical_record_id,
                            'bill_number' => $bno->bill_number,
                            'lab_type_id' => $input['test_id'][$i],
                            'tested_from' => $input['tested_from'][$i],
                            'notes' => $input['notes'][$i],
                            'created_by' => $request->user()->id,
                            'updated_by' => $request->user()->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    endif;
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('lab.radiology.index')->with('success','Lab Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LabRadiology::where('medical_record_id', $id)->delete();
        return redirect()->route('lab.radiology.index')
                        ->with('success','Lab Record deleted successfully');
    }

    public function editresult($id){
        $lab_records = LabRadiology::where('medical_record_id', $id)->get();
        $retina_od = DB::table('lab_images')->where('medical_record_id', $id)->where('type', 'od')->get();
        $retina_os = DB::table('lab_images')->where('medical_record_id', $id)->where('type', 'os')->get();
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $labtests = DB::table('lab_types')->where('category_id', 2)->get();
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('lab.radiology.result', compact('lab_records', 'mrecord', 'patient', 'doctor', 'age', 'labtests', 'retina_od', 'retina_os'));
    }

    public function updateresult(Request $request, $id){
        $input = $request->all();
        try{
            if($input['lab_id']):
                for($i=0; $i<count($input['lab_id']); $i++):
                    if($input['lab_id'][$i] > 0):
                        LabRadiology::where(['medical_record_id' => $id, 'id' => $input['lab_id'][$i]])->update(['lab_result' => $input['lab_result'][$i], 'result_updated_on' => Carbon::now()->toDateTimeString(), 'updated_by' => $request->user()->id]);
                    endif;
                endfor;
            endif;
            DB::table('lab_images')->where('medical_record_id', $id)->delete();
            if(isset($input['retina_img'])):
                for($i=0; $i<count($input['retina_img']); $i++):
                    $fpath = 'assets/lab/radiology/'.$id.'/file_'.$i.'.png';
                    Storage::disk('public')->put($fpath, base64_decode(str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', ' '], ['', '', '+'], $input['retina_img'][$i])));
                    DB::table('lab_images')->insert([
                        'medical_record_id' => $id,
                        'lab_test_id' => $input['lab_test_id'][$i],
                        'lab_type_id' => 2,
                        'img' => $fpath,
                        'description' => $input['retina_desc'][$i],
                        'type' => $input['retina_type'][$i],
                    ]);
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('lab.radiology.index')->with('success','Lab Result updated successfully');
    }
}
