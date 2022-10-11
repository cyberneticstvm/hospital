<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ascan;
use Carbon\Carbon;
use DB;

class AscanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:ascan-list|ascan-create|ascan-edit|ascan-delete', ['only' => ['index','store']]);
        $this->middleware('permission:ascan-create', ['only' => ['create','store']]);
        $this->middleware('permission:ascan-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ascan-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $ascans = Ascan::leftJoin('patient_medical_records AS m', 'ascans.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'ascans.patient_id', '=', 'p.id')->selectRaw("ascans.*, p.patient_name, p.patient_id, ascans.medical_record_id")->whereDate('ascans.created_at', Carbon::today())->orderByDesc("ascans.id")->get();
        return view('ascan.index', compact('ascans'));
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
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        $input['fee'] = 0.00;
        $ascan = Ascan::create($input);
        return redirect()->route('ascan.index')->with('success','Record created successfully');
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
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('ascan.create', compact('mrecord', 'patient', 'doctor', 'age'));
        else:
            return redirect("/ascan/")->withErrors('No records found.');
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
        $ascan = Ascan::find($id);
        $mrecord = DB::table('patient_medical_records')->find($ascan->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('ascan.edit', compact('mrecord', 'patient', 'doctor', 'age', 'ascan'));
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
        $input['updated_by'] = $request->user()->id;
        $input['fee'] = 0.00;
        $asc = Ascan::find($id);
        $asc->update($input);
        return redirect()->route('ascan.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ascan::find($id)->delete();
        return redirect()->route('ascan.index')
                        ->with('success','Record deleted successfully');
    }
}
