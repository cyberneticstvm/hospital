<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalFitness;
use Carbon\Carbon;
use DB;

class MedicalFitnessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:medical-fitness-list|medical-fitness-create|medical-fitness-edit|medical-fitness-delete', ['only' => ['index','store']]);
        $this->middleware('permission:medical-fitness-create', ['only' => ['create','store']]);
        $this->middleware('permission:medical-fitness-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:medical-fitness-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $mfitnesses = MedicalFitness::leftJoin('patient_medical_records AS m', 'medical_fitnesses.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'medical_fitnesses.patient', '=', 'p.id')->leftJoin('branches as b', 'b.id', '=', 'm.branch')->select('medical_fitnesses.id', 'm.id as medical_record_id', 'b.branch_name', 'medical_fitnesses.notes', 'p.patient_name', 'p.patient_id')->orderByDesc('medical_fitnesses.id')->get();
        return view('medical-fitness.index', compact('mfitnesses'));
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
            'head' => 'required',
            'fitness_advice' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try{
            $mfit = MedicalFitness::create($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('mfit.index')->with('success','Record created successfully');
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
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $branch = DB::table('branches')->find($mrecord->branch);
            $heads = DB::table('medical_fitness_heads')->get();
            $surgery = DB::table('surgeries as s')->leftJoin('surgery_types as st', 'st.id', '=', 's.surgery_type')->select('st.fitness_advice')->where('s.medical_record_id', $request->medical_record_id)->latest('s.id')->first();
            $stypes = DB::table('surgery_types')->get();
            return view('medical-fitness.create', compact('mrecord', 'patient', 'branch', 'surgery', 'stypes', 'heads'));
        else:
            return redirect("/medical-fitness/")->withErrors('No records found.');
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
        $mfit = MedicalFitness::find($id);
        $mrecord = DB::table('patient_medical_records')->find($mfit->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $branch = DB::table('branches')->find($mrecord->branch);
        $stypes = DB::table('surgery_types')->get();
        $heads = DB::table('medical_fitness_heads')->get();
        return view('medical-fitness.edit', compact('mrecord', 'patient', 'branch', 'mfit', 'stypes', 'heads'));
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
            'notes' => 'required',
            'fitness_advice' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        try{
            $mfit = MedicalFitness::find($id);
            $mfit->update($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('mfit.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MedicalFitness::find($id)->delete();
        return redirect()->route('mfit.index')
                        ->with('success','Record deleted successfully');
    }
}
