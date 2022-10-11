<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tonometry;
use Carbon\Carbon;
use DB;

class TonometryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:tonometry-list|tonometry-create|tonometry-edit|tonometry-delete', ['only' => ['index','store']]);
        $this->middleware('permission:tonometry-create', ['only' => ['create','store']]);
        $this->middleware('permission:tonometry-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tonometry-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $tonometries = Tonometry::leftJoin('patient_medical_records AS m', 'tonometries.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'tonometries.patient_id', '=', 'p.id')->selectRaw("tonometries.*, p.patient_name, p.patient_id, tonometries.medical_record_id")->whereDate('tonometries.created_at', Carbon::today())->orderByDesc("tonometries.id")->get();
        return view('tonometry.index', compact('tonometries'));
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
        $tonometry = Tonometry::create($input);
        return redirect()->route('tonometry.index')->with('success','Record created successfully');
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
            $powers = DB::table('eye_powers')->where('category', 'tonometry')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('tonometry.create', compact('mrecord', 'patient', 'doctor', 'age', 'powers'));
        else:
            return redirect("/tonometry/")->withErrors('No records found.');
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
        $tonometry = Tonometry::find($id);
        $mrecord = DB::table('patient_medical_records')->find($tonometry->medical_record_id);
        $powers = DB::table('eye_powers')->where('category', 'tonometry')->get();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('tonometry.edit', compact('mrecord', 'patient', 'doctor', 'age', 'powers', 'tonometry'));
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
        $to = Tonometry::find($id);
        $to->update($input);
        return redirect()->route('tonometry.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tonometry::find($id)->delete();
        return redirect()->route('tonometry.index')
                        ->with('success','Record deleted successfully');
    }
}
