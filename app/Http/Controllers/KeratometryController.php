<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keratometry;
use Carbon\Carbon;
use DB;

class KeratometryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:keratometry-list|keratometry-create|keratometry-edit|keratometry-delete', ['only' => ['index','store']]);
        $this->middleware('permission:keratometry-create', ['only' => ['create','store']]);
        $this->middleware('permission:keratometry-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:keratometry-delete', ['only' => ['destroy']]);
    }

    public function index(){
        $keratometries = Keratometry::leftJoin('patient_medical_records AS m', 'keratometries.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'keratometries.patient_id', '=', 'p.id')->selectRaw("keratometries.*, p.patient_name, p.patient_id, keratometries.medical_record_id")->whereDate('keratometries.created_at', Carbon::today())->orderByDesc("keratometries.id")->get();
        return view('keratometry.index', compact('keratometries'));
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
        $keratometry = Keratometry::create($input);
        return redirect()->route('keratometry.index')->with('success','Record created successfully');
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
            $powers = DB::table('eye_powers')->where('category', 'keratometry')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('keratometry.create', compact('mrecord', 'patient', 'doctor', 'age', 'powers'));
        else:
            return redirect("/keratometry/")->withErrors('No records found.');
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
        $keratometry = Keratometry::find($id);
        $mrecord = DB::table('patient_medical_records')->find($keratometry->medical_record_id);
        $powers = DB::table('eye_powers')->where('category', 'keratometry')->get();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('keratometry.edit', compact('mrecord', 'patient', 'doctor', 'age', 'powers', 'keratometry'));
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
        $ke = Keratometry::find($id);
        $ke->update($input);
        return redirect()->route('keratometry.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Keratometry::find($id)->delete();
        return redirect()->route('keratometry.index')
                        ->with('success','Record deleted successfully');
    }
}
