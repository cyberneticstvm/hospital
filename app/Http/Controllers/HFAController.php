<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use App\Models\HFA;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class HFAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:hfa-list|hfa-create|hfa-edit|hfa-delete', ['only' => ['index','store']]);
        $this->middleware('permission:hfa-create', ['only' => ['create','store']]);
        $this->middleware('permission:hfa-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hfa-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $hfas = HFA::leftJoin('patient_medical_records AS m', 'h_f_a_s.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'h_f_a_s.patient_id', '=', 'p.id')->selectRaw("h_f_a_s.*, p.patient_name, p.patient_id, h_f_a_s.medical_record_id")->where('h_f_a_s.branch', $this->branch)->whereIn('h_f_a_s.status', [1,2])->orderByDesc("h_f_a_s.id")->get();
        return view('hfa.index', compact('hfas'));
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
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        if($request->document):
            $fpath = 'hfa/'.$request->medical_record_id;
            $input['document'] = Storage::disk('public')->putFile($fpath, $request->document);
        endif;
        try{
            $hfa = HFA::create($input);
            if(!empty($input['procedure'])):
                for($i=0; $i<count($request->procedure); $i++):
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee,
                        'type' => 'H',
                        'created_by' => $request->user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('hfa.index')->with('success','Record created successfully');
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
        $mrecord = DB::table('patient_medical_records')->find($request->medical_record_id);
        if($mrecord):
            $procedures = DB::table('procedures')->where('type', 'H')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            return view('hfa.create', compact('mrecord', 'patient', 'doctor', 'procedures'));
        else:
            return redirect("/hfa")->withErrors('No records found.');
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
        $hfa = HFA::find($id);
        $mrecord = DB::table('patient_medical_records')->find($hfa->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $procedures = DB::table('procedures')->where('type', 'H')->get();
        $status = DB::table('types')->where('category', 'surgery')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $hfa->medical_record_id)->where('type', 'H')->get();
        return view('hfa.edit', compact('mrecord', 'patient', 'doctor', 'hfa', 'procedures', 'advised', 'status'));
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
            'status' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        if($request->document):
            $fpath = 'hfa/'.$request->medical_record_id;
            $input['document'] = Storage::disk('public')->putFile($fpath, $request->document);
        endif;
        try{
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'H')->delete();
            $hfa = HFA::find($id);
            $hfa->update($input);
            if(!empty($input['procedure'])):
                for($i=0; $i<count($request->procedure); $i++):
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee,
                        'type' => 'H',
                        'created_by' => $request->user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('hfa.index')->with('success','Record created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hfa = HFA::find($id);
        DB::table('patient_procedures')->where('medical_record_id', $hfa->medical_record_id)->where('type', 'H')->delete();
        $hfa->delete();
        return redirect()->route('hfa.index')
                        ->with('success','Record deleted successfully');
    }
}
