<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Spectacle;
use Carbon\Carbon;
use DB;

class SpectacleController extends Controller
{
    private $branch;

    function __construct(){
         $this->middleware('permission:spectacle-list|spectacle-create|spectacle-edit|spectacle-delete', ['only' => ['index','store']]);
         $this->middleware('permission:spectacle-create', ['only' => ['create','store']]);
         $this->middleware('permission:spectacle-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:spectacle-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spectacles = Spectacle::leftJoin('patient_medical_records AS m', 'spectacles.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('patient_references as pr', 'pr.id', '=', 'spectacles.medical_record_id')->leftJoin('users AS u', 'spectacles.created_by', '=', 'u.id')->selectRaw("spectacles.id, spectacles.medical_record_id, p.id as pid, p.patient_name, p.patient_id, u.name AS optometrist, DATE_FORMAT(spectacles.created_at, '%d/%b/%Y') AS pdate, pr.consultation_type")->where('m.branch', $this->branch)->whereDate('spectacles.created_at', Carbon::today())->orderByDesc("spectacles.id")->get();
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
        $medical_record = []; $reading_adds = DB::table('eye_powers')->where('category', 'reading_add')->get();
        return view('spectacle.fetch', compact('medical_record', 'reading_adds'));
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
        $input['updated_by'] = $request->user()->id;
        $input['fee'] = 0.00;
        if($request->ctype == 5):
            $input['fee'] = DB::table('branches')->where('id', $this->branch)->value('fee_vision');
        endif;        
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
            $pref = DB::table('patient_references')->where('id', $mrecord->mrn)->first();
            $reading_adds = DB::table('eye_powers')->where('category', 'reading_add')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('spectacle.create', compact('mrecord', 'patient', 'doctor', 'age', 'reading_adds', 'pref'));
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
        $reading_adds = DB::table('eye_powers')->where('category', 'reading_add')->get();
        $mrecord = DB::table('patient_medical_records')->find($spectacle->medical_record_id);
        $pref = DB::table('patient_references')->where('id', $mrecord->mrn)->first();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('spectacle.edit', compact('mrecord', 'patient', 'doctor', 'spectacle', 'age', 'reading_adds', 'pref'));
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
        $input['fee'] = 0.00;
        if($request->ctype == 5):
            $input['fee'] = DB::table('branches')->where('id', $this->branch)->value('fee_vision');
        endif;
        $spectacle = Spectacle::find($id);
        $input['created_by'] = $spectacle->getOriginal('created_by');
        if(Auth::user()->roles->first()->name == 'Admin'):
            $input['updated_by'] = $spectacle->getOriginal('updated_by');
        else:
            $input['updated_by'] = $request->user()->id;
        endif;
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
