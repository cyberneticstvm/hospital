<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Spectacle;
use Carbon\Carbon;
use DB;

class SpectacleController extends Controller
{
    function __construct(){
         $this->middleware('permission:spectacle-list|spectacle-create|spectacle-edit|spectacle-delete', ['only' => ['index','store']]);
         $this->middleware('permission:spectacle-create', ['only' => ['create','store']]);
         $this->middleware('permission:spectacle-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:spectacle-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spectacles = Spectacle::leftJoin('patient_medical_records AS m', 'spectacles.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('users AS u', 'spectacles.created_by', '=', 'u.id')->selectRaw("spectacles.id, spectacles.medical_record_id, p.patient_name, p.patient_id, u.name AS optometrist, DATE_FORMAT(spectacles.created_at, '%d/%b/%Y') AS pdate")->get();
        return view('spectacle.index', compact('spectacles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    public function fetch(){
        $medical_record = [];
        return view('spectacle.fetch', compact('medical_record'));
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
        ]);
        $input = $request->all();
        $input['review_date'] = (!empty($request->review_date)) ? Carbon::createFromFormat('d/M/Y', $request->review_date)->format('Y-m-d') : NULL;
        $input['created_by'] = $request->user()->id;
        $spectacle = Spectacle::create($input);
        return redirect()->route('spectacle.index')->with('success','Record created successfully');
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
        if($mrecord):
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('spectacle.create', compact('mrecord', 'patient', 'doctor', 'age'));
        else:
            return redirect("/spectacle/fetch/")->withErrors('No records found.');
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
        $spectacle = Spectacle::find($id);
        $mrecord = DB::table('patient_medical_records')->find($spectacle->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('spectacle.edit', compact('mrecord', 'patient', 'doctor', 'spectacle', 'age'));
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
        ]);
        $input = $request->all();
        $input['review_date'] = (!empty($request->review_date)) ? Carbon::createFromFormat('d/M/Y', $request->review_date)->format('Y-m-d') : NULL;
        $input['created_by'] = $request->user()->id;
        $spectacle = Spectacle::find($id);
        $spectacle->update($input);
        return redirect()->route('spectacle.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Spectacle::find($id)->delete();
        return redirect()->route('spectacle.index')
                        ->with('success','Record deleted successfully');
    }
}
