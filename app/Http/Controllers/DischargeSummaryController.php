<?php

namespace App\Http\Controllers;

use App\Models\DischargeSummary;
use App\Models\DischargeSummaryDiagnosis;
use App\Models\DischargeSummaryInstruction;
use App\Models\DischargeSummaryMedication;
use App\Models\DischargeSummaryProcedure;
use App\Models\DischargeSummaryReview;
use App\Models\doctor;
use App\Models\PatientMedicalRecord;
use App\Models\PostOperativeInstruction;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Exception;

class DischargeSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:discharge-summary-list|discharge-summary-create|discharge-summary-edit|discharge-summary-delete', ['only' => ['index','store']]);
         $this->middleware('permission:discharge-summary-create', ['only' => ['create','store']]);
         $this->middleware('permission:discharge-summary-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:discharge-summary-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $records = DischargeSummary::orderByDesc('id')->get();
        return view('discharge-summary.index', compact('records'));
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
        $this->validate($request, [
            'medical_record_id' => 'required',
            'patient_id' => 'required',
            'branch' => 'required',
            'medication' => 'required',
        ]);
        $input = $request->all();
        try{
            DB::transaction(function() use ($input, $request) {
                $ds = DischargeSummary::create($input);
                $diagnosis = []; $procedures = []; $medicines = []; $instructions = []; $reviews = [];
                if($request->diagnosis):            
                    foreach($request->diagnosis as $key => $val):
                        $diagnosis [] = [
                            'summary_id' => $ds->id,
                            'diagnosis' => $val,
                        ];
                    endforeach;
                endif;
                if($request->procedures):
                    foreach($request->procedures as $key => $val):
                        $procedures [] = [
                            'summary_id' => $ds->id,
                            'procedure' => $val,
                        ];
                    endforeach;
                endif;
                foreach($request->product_id as $key => $val):
                    $medicines [] = [
                        'summary_id' => $ds->id,
                        'medicine' => $val,
                        'type' => $request->medicine_type[$key],
                        'qty' => $request->qty[$key],
                        'notes' => $request->notes[$key],
                    ];
                endforeach;
                foreach($request->instructions as $key => $val):
                    $instructions [] = [
                        'summary_id' => $ds->id,
                        'instruction_id' => $val,
                    ];
                endforeach;
                foreach($request->review_date as $key => $val):
                    $reviews [] = [
                        'summary_id' => $ds->id,
                        'review_date' => $val,
                        'review_time' => $request->review_time[$key],
                    ];
                endforeach;
                if(!empty($diagnosis)) DischargeSummaryDiagnosis::insert($diagnosis);
                if(!empty($procedures)) DischargeSummaryProcedure::insert($procedures);
                DischargeSummaryMedication::insert($medicines);
                DischargeSummaryInstruction::insert($instructions);
                DischargeSummaryReview::insert($reviews);
            });
        }catch(Exception $e){
            return back()->with('error', $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('dsummary.index')->with('success', 'Record saved successfully');
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
            'medical_record_id' => 'required',
        ]);
        $mrecord = PatientMedicalRecord::find($request->medical_record_id);
        if($mrecord):
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $diagnosis = DB::table('diagnosis')->pluck('diagnosis_name', 'id')->all();
            $procedures = DB::table('procedures')->where('type', 'S')->pluck('name', 'id')->all();
            $medicines = DB::table('products')->pluck('product_name', 'id')->all();
            $types = DB::table('medicine_types')->pluck('name', 'id')->all();
            $postinstructions = PostOperativeInstruction::all();
            $doctors = doctor::all();
            return view('discharge-summary.create', compact('mrecord', 'patient', 'diagnosis', 'procedures', 'medicines', 'postinstructions', 'doctors', 'types'));
        else:
            return redirect()->back()->withErrors("No records found")->withInput($request->all());
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
        $ds = DischargeSummary::find($id);
        $mrecord = PatientMedicalRecord::find($ds->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $diagnosis = DB::table('diagnosis')->pluck('diagnosis_name', 'id')->all();
        $procedures = DB::table('procedures')->where('type', 'S')->pluck('name', 'id')->all();
        $medicines = DB::table('products')->pluck('product_name', 'id')->all();
        $postinstructions = PostOperativeInstruction::all();
        $doctors = doctor::all(); $types = DB::table('medicine_types')->pluck('name', 'id')->all();
        return view('discharge-summary.edit', compact('mrecord', 'patient', 'diagnosis', 'procedures', 'medicines', 'postinstructions', 'ds', 'doctors', 'types'));
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
            'medical_record_id' => 'required',
            'patient_id' => 'required',
            'branch' => 'required',
            'medication' => 'required',
        ]);
        $input = $request->all();
        try{
            DB::transaction(function() use ($input, $request, $id) {
                $ds = DischargeSummary::find($id);
                $ds->update($input);
                $diagnosis = []; $procedures = []; $medicines = []; $instructions = []; $reviews = [];
                if($request->diagnosis):           
                    foreach($request->diagnosis as $key => $val):
                        if($val):
                            $diagnosis [] = [
                                'summary_id' => $ds->id,
                                'diagnosis' => $val,
                            ];
                        endif;
                    endforeach;
                endif;
                if($request->procedures):
                    foreach($request->procedures as $key => $val):
                        if($val):
                            $procedures [] = [
                                'summary_id' => $ds->id,
                                'procedure' => $val,
                            ];
                        endif;
                    endforeach;
                endif;
                foreach($request->product_id as $key => $val):
                    $medicines [] = [
                        'summary_id' => $ds->id,
                        'medicine' => $val,
                        'type' => $request->medicine_type[$key],
                        'qty' => $request->qty[$key],
                        'notes' => $request->notes[$key],
                    ];
                endforeach;
                foreach($request->instructions as $key => $val):
                    $instructions [] = [
                        'summary_id' => $ds->id,
                        'instruction_id' => $val,
                    ];
                endforeach;
                foreach($request->review_date as $key => $val):
                    $reviews [] = [
                        'summary_id' => $ds->id,
                        'review_date' => $val,
                        'review_time' => $request->review_time[$key],
                    ];
                endforeach;
                DischargeSummaryDiagnosis::where('summary_id', $id)->delete();
                DischargeSummaryProcedure::where('summary_id', $id)->delete();
                DischargeSummaryMedication::where('summary_id', $id)->delete();
                DischargeSummaryInstruction::where('summary_id', $id)->delete();
                DischargeSummaryReview::where('summary_id', $id)->delete();
                if(!empty($diagnosis)) DischargeSummaryDiagnosis::insert($diagnosis);
                if(!empty($procedures)) DischargeSummaryProcedure::insert($procedures);
                DischargeSummaryMedication::insert($medicines);
                DischargeSummaryInstruction::insert($instructions);
                DischargeSummaryReview::insert($reviews);
            });
        }catch(Exception $e){
            return back()->with('error', $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('dsummary.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DischargeSummary::find($id)->delete();
        return redirect()->route('dsummary.index')->with('success', 'Record deleted successfully');
    }
}
