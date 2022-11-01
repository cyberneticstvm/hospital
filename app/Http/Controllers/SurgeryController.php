<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Surgery;
use Carbon\Carbon;
use DB;

class SurgeryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:surgery-list|surgery-create|surgery-edit|surgery-delete', ['only' => ['index','store']]);
        $this->middleware('permission:surgery-create', ['only' => ['create','store']]);
        $this->middleware('permission:surgery-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:surgery-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
   }

    public function index()
    {
        $surgeries = DB::table('surgeries as s')->leftJoin('patient_registrations as p', 's.patient_id', '=', 'p.id')->leftJoin('doctors as d', 's.doctor_id', '=', 'd.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 's.medical_record_id')->leftJoin('surgery_types as st', 's.surgery_type', '=', 'st.id')->leftjoin('doctors as doc', 's.surgeon', '=', 'doc.id')->selectRaw("s.id, p.id as pid, p.patient_name, p.patient_id, d.doctor_name, s.medical_record_id, DATE_FORMAT(s.surgery_date, '%d/%b/%Y') AS sdate, CASE WHEN pmr.is_patient_surgery = 'N' THEN 'No' ELSE 'Yes' END AS is_patient_surgery, st.surgery_name, doc.doctor_name as surgeon")->orderBy('s.id', 'desc')->get();
        return view('surgery.index', compact('surgeries'));
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
        //
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
    public function edit($id)
    {
        $stypes = DB::table('surgery_types')->get();
        $doctors = DB::table('doctors')->get();
        $surgery = Surgery::find($id);
        $patient = DB::table('patient_registrations')->find($surgery->patient_id);
        $doctor = DB::table('doctors')->find($surgery->doctor_id);
        return view('surgery.edit', compact('surgery', 'stypes', 'patient', 'doctor', 'doctors'));
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
            'surgery_date' => 'required',
            'surgery_type' => 'required',
            'surgeon' => 'required'
        ]);
        $input = $request->all();
        $input['surgery_date'] = (!empty($request->surgery_date)) ? Carbon::createFromFormat('d/M/Y', $request['surgery_date'])->format('Y-m-d') : NULL;
        $input['updated_by'] = $request->user()->id;        
        $surgery = Surgery::find($id);
        $input['branch'] = $surgery->getOriginal('branch');
        $surgery->update($input);        
        return redirect()->route('surgery.index')->with('success','Surgery updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $surgery = Surgery::find($id);
        $surgery->delete();
        DB::table('patient_medical_records')->where('id', $surgery->medical_record_id)->update(['is_surgery' => 0]);
        return redirect()->route('surgery.index')
                        ->with('success','Surgery deleted successfully');
    }
}
