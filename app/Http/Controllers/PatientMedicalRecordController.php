<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientMedicalRecord as PMRecord;
use App\Models\User;
use Carbon\Carbon;
use DB;

class PatientMedicalRecordController extends Controller
{
    private $branch;

    function __construct()
    {
         $this->middleware('permission:patient-medical-record-list|patient-medical-record-create|patient-medical-record-edit|patient-medical-record-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-medical-record-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-medical-record-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-medical-record-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medical_records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('doctors as doc', 'pmr.doctor_id', '=', 'doc.id')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->leftJoin('consultation_types as c', 'c.id', '=', 'pref.consultation_type')->select('pmr.id', 'pmr.mrn', DB::raw("CASE WHEN pref.consultation_type = 4 THEN CONCAT_WS(' ', pr.patient_name, '- (Camp)') WHEN (pref.consultation_type = 2 OR pref.consultation_type = 3) THEN CONCAT_WS(' ', pr.patient_name, '- (Cert)') ELSE pr.patient_name END AS patient_name"), 'pr.patient_id', 'pr.age', 'doc.doctor_name', 'pmr.status', 'pmr.diagnosis', DB::Raw("DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS rdate, IFNULL(DATE_FORMAT(pmr.review_date, '%d/%b/%Y'), '--') AS review_date"), DB::raw("CASE WHEN pmr.updated_at IS NULL THEN 'no' ELSE 'yes' END AS cstatus"))->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1,3,7,4])->when(Auth::user()->roles->first()->name != 'Admin', function($query){
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->orderByDesc('pmr.id')->get();
        //->when(Auth::user()->roles->first()->name != 'Admin', function($query){
            //return $query->where('pmr.doctor_id', Auth::user()->id);
        //})->
        $ccount = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1,3,7,4])->whereNull('pmr.updated_at')->when(Auth::user()->roles->first()->name != 'Admin', function($query){
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->count();
        $ccount1 = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->where('pmr.branch', $this->branch)->whereDate('pmr.created_at', Carbon::today())->whereIn('pref.consultation_type', [1,3,7,4])->when(Auth::user()->roles->first()->name != 'Admin', function($query){
            return $query->where('pmr.doctor_id', Auth::user()->doctor_id);
        })->whereNotNull('pmr.updated_at')->count();
        return view('consultation.index', compact('medical_records', 'ccount', 'ccount1'));
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
        if(!$request->symptom_id){
            echo "Please choose symptom";
            die;
        }
        if(!$request->diagnosis_id){
            echo "Please choose diagnosis";
            die;
        }
        if(!$request->doctor_recommondations){
            echo "Please enter doctor recommondations";
            die;
        }
        $input = $request->all();        
        $odospoints = json_decode(stripslashes($input['odospoints']), true);

        $input['review_date'] = ($input['review_date']) ? Carbon::createFromFormat('d/M/Y', $request['review_date'])->format('Y-m-d') : NULL;
        $input['symptoms'] = implode(',', $request->symptom_id);
        $input['diagnosis'] = implode(',', $request->diagnosis_id);
        $input['created_by'] = $request->user()->id;        

        $input['medicine'] = $request->medicine_id;
        $input['dosage'] = $request->dosage;
        $input['dosage1'] = $request->dosage1;

        //$input['is_admission'] = $request->is_admission;

        try{
            $record = PMRecord::create($input);

            if($input['medicine']):
                for($i=0; $i<count($input['medicine']); $i++):
                    if($input['medicine'][$i] > 0):
                        $product = DB::table('products')->find($input['medicine'][$i]);
                        DB::table('patient_medicine_records')->insert([
                            'medical_record_id' => $record->id,
                            'mrn' => $request->mrn,
                            'medicine' => $input['medicine'][$i],
                            'dosage' => $input['dosage'][$i],
                            'qty' => $input['qty'][$i],
                            'tax_percentage' => $product->tax_percentage,
                            'notes' => $input['notes'][$i],
                            'updated_by' => $request->user()->id,
                            'updated_at' => Carbon::now(),
                        ]);
                    endif;
                endfor;
            endif;
            if($odospoints):
                foreach($odospoints as $value):
                    DB::table('patient_medical_records_vision')->insert([
                        'medical_record_id' => $record->id,
                        'description' => $value['description'],
                        'color' => $value['color'],
                        'img_type' => $value['type'],
                    ]);
                endforeach;
            endif;
            if(isset($input['retina_img'])):
                for($i=0; $i<count($input['retina_img']); $i++):
                    DB::table('patient_medical_records_retina')->insert([
                        'medical_record_id' => $record->id,
                        'retina_img' => $input['retina_img'][$i],
                        'description' => $input['retina_desc'][$i],
                        'retina_type' => $input['retina_type'][$i],
                    ]);
                endfor;
            endif;
            echo "success";
        }catch(Exception $e){
            throw $e;
        }
        
        //return redirect()->route('consultation.index')->with('success','Medical Record created successfully');
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
    public function edit(PMRecord $precord, $id)
    {
        $record = PMRecord::find($id);
        if(Auth::user()->roles->first()->name != 'Admin'):
            if(!Gate::allows('update-medical-record', $record)){
                abort(403, 'Oops.. You are not allowed to perform this action!');
            }     
        endif;
        $patient = DB::table('patient_registrations')->find($record->patient_id);
        $symptoms = DB::table('symptoms')->get();
        $diagnosis = DB::table('diagnosis')->get();
        $medicines = DB::table('products')->get();
        $mtypes = DB::table('medicine_types')->get();
        $mrns = DB::table('patient_references')->where('patient_id', $patient->id)->orderByDesc('id')->get();
        //$medicines = DB::table('products as p')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.id', DB::raw("CONCAT_WS(' - ', p.product_name, t.name) AS product_name"))->get();
        $dosages = DB::table('dosages')->get();
        $doctor = DB::table('doctors')->find($record->doctor_id);
        //$spectacle = DB::table('spectacles')->where('patient_id', $record->patient_id)->orderByDesc('id')->first();
        $spectacle = DB::table('spectacles')->where('medical_record_id', $id)->first();
        $medicine_record = DB::table('patient_medicine_records')->where('medical_record_id', $id)->get();
        $retina_od = DB::table('patient_medical_records_retina')->where('medical_record_id', $id)->where('retina_type', 'od')->get();
        $retina_os = DB::table('patient_medical_records_retina')->where('medical_record_id', $id)->where('retina_type', 'os')->get();
        $vision = DB::table('patient_medical_records_vision')->where('medical_record_id', $id)->get();
        $vextras = DB::table('vision_extras')->where('cat_id', '>', 0)->get();
        return view('consultation.edit-medical-records', compact('record', 'patient', 'symptoms', 'doctor', 'diagnosis', 'medicines', 'dosages', 'medicine_record', 'spectacle', 'retina_od', 'retina_os', 'vision', 'vextras', 'mtypes', 'mrns'));
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
        /*if(empty($request->symptom_id)){
            echo "Please choose symptom";
            die;
        }
        if(empty($request->diagnosis_id)){
            echo "Please choose diagnosis";
            die;
        }
        if(empty($request->doctor_recommondations)){
            echo "Please enter doctor recommondations";
            die;
        }*/
        $img1 = NULL; $img2 = NULL; $img3 = NULL; $img4 = NULL; $img5 = NULL; $img6 = NULL; $img7 = NULL; $img8 = NULL;
        $input = $request->all();

        $odospoints = json_decode(stripslashes($input['odospoints']), true);

        $input['review_date'] = ($input['review_date']) ? Carbon::createFromFormat('d/M/Y', $request['review_date'])->format('Y-m-d') : NULL;
        $input['symptoms'] = ($request->symptom_id) ? implode(',', $request->symptom_id) : 0;
        $input['diagnosis'] = ($request->diagnosis_id) ? implode(',', $request->diagnosis_id) : 0;

        $input['sel_1_od'] = ($request->sel_1_od) ? implode(',', $request->sel_1_od) : 0;
        $input['sel_1_os'] = ($request->sel_1_os) ? implode(',', $request->sel_1_os) : 0;
        $input['sel_2_od'] = ($request->sel_2_od) ? implode(',', $request->sel_2_od) : 0;
        $input['sel_2_os'] = ($request->sel_2_os) ? implode(',', $request->sel_2_os) : 0;
        $input['sel_3_od'] = ($request->sel_3_od) ? implode(',', $request->sel_3_od) : 0;
        $input['sel_3_os'] = ($request->sel_3_os) ? implode(',', $request->sel_3_os) : 0;
        $input['sel_4_od'] = ($request->sel_4_od) ? implode(',', $request->sel_4_od) : 0;
        $input['sel_4_os'] = ($request->sel_4_os) ? implode(',', $request->sel_4_os) : 0;
        $input['sel_5_od'] = ($request->sel_5_od) ? implode(',', $request->sel_5_od) : 0;
        $input['sel_5_os'] = ($request->sel_5_os) ? implode(',', $request->sel_5_os) : 0;
        $input['sel_6_od'] = ($request->sel_6_od) ? implode(',', $request->sel_6_od) : 0;
        $input['sel_6_os'] = ($request->sel_6_os) ? implode(',', $request->sel_6_os) : 0;
        $input['sel_7_od'] = ($request->sel_7_od) ? implode(',', $request->sel_7_od) : 0;
        $input['sel_7_os'] = ($request->sel_7_os) ? implode(',', $request->sel_7_os) : 0;
        $input['sel_8_od'] = ($request->sel_8_od) ? implode(',', $request->sel_8_od) : 0;
        $input['sel_8_os'] = ($request->sel_8_os) ? implode(',', $request->sel_8_os) : 0;
        $input['sel_9_od'] = ($request->sel_9_od) ? implode(',', $request->sel_9_od) : 0;
        $input['sel_9_os'] = ($request->sel_9_os) ? implode(',', $request->sel_9_os) : 0;
        $input['sel_10_od'] = ($request->sel_10_od) ? implode(',', $request->sel_10_od) : 0;
        $input['sel_10_os'] = ($request->sel_10_os) ? implode(',', $request->sel_10_os) : 0;
        $input['sel_11_od'] = ($request->sel_11_od) ? implode(',', $request->sel_11_od) : 0;
        $input['sel_11_os'] = ($request->sel_11_os) ? implode(',', $request->sel_11_os) : 0;
        $input['sel_12_od'] = ($request->sel_12_od) ? implode(',', $request->sel_12_od) : 0;
        $input['sel_12_os'] = ($request->sel_12_os) ? implode(',', $request->sel_12_os) : 0;
        $input['sel_13_od'] = ($request->sel_13_od) ? implode(',', $request->sel_13_od) : 0;
        $input['sel_13_os'] = ($request->sel_13_os) ? implode(',', $request->sel_13_os) : 0;
        $input['sel_14_od'] = ($request->sel_14_od) ? implode(',', $request->sel_14_od) : 0;
        $input['sel_14_os'] = ($request->sel_14_os) ? implode(',', $request->sel_14_os) : 0;
        $input['sel_15_od'] = ($request->sel_15_od) ? implode(',', $request->sel_15_od) : 0;
        $input['sel_15_os'] = ($request->sel_15_os) ? implode(',', $request->sel_15_os) : 0;
        $input['sel_16_od'] = ($request->sel_16_od) ? implode(',', $request->sel_16_od) : 0;
        $input['sel_16_os'] = ($request->sel_16_os) ? implode(',', $request->sel_16_os) : 0;
        $input['sel_17_od'] = ($request->sel_17_od) ? implode(',', $request->sel_17_od) : 0;
        $input['sel_17_os'] = ($request->sel_17_os) ? implode(',', $request->sel_17_os) : 0;
        $input['sel_18_od'] = ($request->sel_18_od) ? implode(',', $request->sel_18_od) : 0;
        $input['sel_18_os'] = ($request->sel_18_os) ? implode(',', $request->sel_18_os) : 0;
        $input['sel_19_od'] = ($request->sel_19_od) ? implode(',', $request->sel_19_od) : 0;
        $input['sel_19_os'] = ($request->sel_19_os) ? implode(',', $request->sel_19_os) : 0;
        $input['sel_20_od'] = ($request->sel_20_od) ? implode(',', $request->sel_20_od) : 0;
        $input['sel_20_os'] = ($request->sel_20_os) ? implode(',', $request->sel_20_os) : 0;
        
        $input['medicine'] = $request->medicine_id;
        $input['dosage'] = $request->dosage;
        $input['dosage1'] = $request->dosage1;

        //$input['is_admission'] = $request->is_admission;
        $record = PMRecord::find($id);
        $input['created_by'] = $record->getOriginal('created_by');

        try{
            //$record->update($input);
            DB::table("patient_medicine_records")->where('mrn', $request->mrn)->delete();
            DB::table("patient_medical_records_vision")->where('medical_record_id', $record->id)->delete();
            DB::table("patient_medical_records_retina")->where('medical_record_id', $record->id)->delete();
            if($input['medicine']):
                for($i=0; $i<count($input['medicine']); $i++):
                    if($input['medicine'][$i] > 0):
                        DB::table('patient_medicine_records')->insert([
                            'medical_record_id' => $record->id,
                            'mrn' => $request->mrn,
                            'medicine_type' => $input['medicine_type'][$i],
                            'eye' => $input['eye'][$i],
                            'medicine' => $input['medicine'][$i],
                            'dosage' => $input['dosage'][$i],
                            'duration' => $input['duration'][$i],
                            'qty' => $input['qty'][$i],
                            'price' => $input['price'][$i],
                            'discount' => $input['discount'][$i],
                            'tax_amount' => $input['tax_amount'][$i],
                            'tax_percentage' => $input['tax_percentage'][$i],
                            'total' => $input['total'][$i],
                            'notes' => $input['notes'][$i],
                            'status' => ($input['price'][$i] > 0) ? 1 : 0,
                            'created_at' => Carbon::now(),
                        ]);
                    endif;
                endfor;
            endif;
            
            if($odospoints):
                foreach($odospoints as $value):
                    if($value['type'] == 'vision_od_img1' && !empty($value['description'])):
                        $img1 = $input['vision_od_img1'];
                    endif;
                    if($value['type'] == 'vision_os_img1' && !empty($value['description'])):
                        $img2 = $input['vision_os_img1'];
                    endif;
                    if($value['type'] == 'vision_od_img2' && !empty($value['description'])):
                        $img3 = $input['vision_od_img2'];
                    endif;
                    if($value['type'] == 'vision_os_img2' && !empty($value['description'])):
                        $img4 = $input['vision_os_img2'];
                    endif;
                    if($value['type'] == 'vision_od_img3' && !empty($value['description'])):
                        $img5 = $input['vision_od_img3'];
                    endif;
                    if($value['type'] == 'vision_os_img3' && !empty($value['description'])):
                        $img6 = $input['vision_os_img3'];
                    endif;
                    if($value['type'] == 'vision_od_img4' && !empty($value['description'])):
                        $img7 = $input['vision_od_img4'];
                    endif;
                    if($value['type'] == 'vision_os_img4' && !empty($value['description'])):
                        $img8 = $input['vision_os_img4'];
                    endif;
                    DB::table('patient_medical_records_vision')->insert([
                        'medical_record_id' => $record->id,
                        'description' => $value['description'],
                        'color' => $value['color'],
                        'img_type' => $value['type'],
                    ]);
                endforeach;
            endif;

            if(isset($input['retina_img'])):
                for($i=0; $i<count($input['retina_img']); $i++):
                    $fpath = 'assets/retina/'.$id.'/file_'.$i.'.png';
                    Storage::disk('public')->put($fpath, base64_decode(str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', ' '], ['', '', '+'], $input['retina_img'][$i])));
                    DB::table('patient_medical_records_retina')->insert([
                        'medical_record_id' => $record->id,
                        'retina_img' => $fpath,
                        'description' => $input['retina_desc'][$i],
                        'retina_type' => $input['retina_type'][$i],
                    ]);
                endfor;
            endif;
            $input['vision_od_img1'] = $img1;
            $input['vision_os_img1'] = $img2;
            $input['vision_od_img2'] = $img3;
            $input['vision_os_img2'] = $img4;
            $input['vision_od_img3'] = $img5;
            $input['vision_os_img3'] = $img6;
            $input['vision_od_img4'] = $img7;
            $input['vision_os_img4'] = $img8;
            $record->update($input);
            //print_r($odospoints);
            /*if($input['review_date']):
                $patient = DB::table('patient_registrations')->find($request->patient_id);
                DB::table('appointments')->upsert(['patient_id' => $patient->id, 'patient_name' => $patient->patient_name, 'gender' => $patient->gender, 'age' => $patient->age, 'mobile_number' => $patient->mobile_number, 'address' => $patient->address, 'branch' => $this->branch, 'doctor' => $request->doctor_id, 'appointment_date' => $input['review_date'], 'appointment_time' => '10:00:00', 'status' => 1, 'notes' => 'REVIEW BOOKING', 'medical_record_id' => 0, 'created_by' => $request->user()->id, 'updated_by' => $request->user()->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()], ['patient_id', 'appointment_date', 'branch']);
            endif;*/
            echo "success";
        }catch(Exception $e){
            throw $e;
        }        
        //return redirect()->route('consultation.index')->with('success','Medical Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PMRecord::find($id)->delete();
        return redirect()->route('consultation.index')
                        ->with('success','Medical Record deleted successfully');
    }
}
