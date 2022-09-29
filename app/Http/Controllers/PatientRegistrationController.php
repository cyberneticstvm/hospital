<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PatientRegistrations;
use Carbon\Carbon;
use DB;

class PatientRegistrationController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:patient-list|patient-create|patient-edit|patient-delete', ['only' => ['index','store']]);
         $this->middleware('permission:patient-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-delete', ['only' => ['destroy']]);
         $this->middleware('permission:patient-search', ['only' => ['fetch']]);
         $this->middleware('permission:consultation-search', ['only' => ['fetchconsultation']]);
         $this->middleware('permission:medical-record-search', ['only' => ['fetchmedicalrecord']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$patients = PatientRegistrations::orderByDesc('id')->get();
        $patients = DB::table('patient_registrations')->select('*', DB::Raw("DATE_FORMAT(created_at, '%d/%b/%Y') AS rdate"))->whereDate('patient_registrations.created_at', Carbon::today())->orderByDesc('patient_registrations.id')->get();
        return view('patient.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = DB::table('city')->get();   
        $states = DB::table('state')->get();    
        $countries = DB::table('country')->get();    
        return view('patient.create', compact('cities', 'states', 'countries'));
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
            'patient_name' => 'required',
            'gender' => 'required',
            'mobile_number' => 'required|min:10|max:10',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);
        $input = $request->all();
        $input['dob'] = (!empty($request->dob)) ? Carbon::createFromFormat('d/M/Y', $request['dob'])->format('Y-m-d') : NULL;

        $next = DB::table('patient_registrations')->selectRaw("CONCAT_WS('-', 'P', LPAD(IFNULL(max(id)+1, 1), 6, '0')) AS id")->first();
        $input['patient_id'] = $next->id;
        $input['created_by'] = $request->user()->id;
        $input['branch'] = $request->session()->get('branch');
        $input['registration_fee'] = DB::table('branches')->where('id', $request->session()->get('branch'))->value('registration_fee');

        $patient = PatientRegistrations::create($input);
        
        return redirect()->route('patient.index')->with('success','Patient created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = PatientRegistrations::find($id);
        $mrecords = DB::table('patient_medical_records')->where('patient_id', $patient->id)->orderByDesc('created_at')->get();
        return view('patient.history', compact('patient', 'mrecords'));
    }

    public function search(){
        $records = []; $search_term = '';
        return view('patient.search', compact('records', 'search_term'));
    }
    public function searchc(){
        $records = []; $search_term = '';
        return view('patient.search-consultation', compact('records', 'search_term'));
    }
    public function searchm(){
        $records = []; $search_term = '';
        return view('patient.search-medical-record', compact('records', 'search_term'));
    }
    public function fetch(Request $request){
        $this->validate($request, [
            'search_term' => 'required',
        ]);
        $input = $request->all();
        $search_term = $request->search_term;
        $records = DB::table('patient_registrations')->select('*', DB::Raw("DATE_FORMAT(created_at, '%d/%b/%Y') AS rdate"))->where('patient_name', 'LIKE', "%{$search_term}%")->orWhere('patient_id', 'LIKE', "%{$search_term}%")->orWhere('mobile_number', 'LIKE', "%{$search_term}%")->orderByDesc('patient_registrations.id')->get();

        return view('patient.search', compact('records', 'search_term'));
    }
    public function fetchconsultation(Request $request){
        $this->validate($request, [
            'search_term' => 'required',
        ]);
        $input = $request->all();
        $search_term = $request->search_term;
        $records = DB::table('patient_references as pr')->leftJoin('patient_medical_records as pmr', 'pr.id', '=', 'pmr.mrn')->leftJoin('doctors', 'pr.doctor_id', '=', 'doctors.id')->leftJoin('patient_registrations as p', 'pr.patient_id', '=', 'p.id')->select('pr.id as reference_id', 'pr.status', 'pmr.id as medical_record_id', 'p.patient_name as pname', 'p.mobile_number', 'p.patient_id as pno', 'doctors.doctor_name', DB::Raw("DATE_FORMAT(pr.created_at, '%d/%b/%Y') AS rdate"))->where('pmr.id', $search_term)->orWhere('p.patient_name', 'LIKE', "%{$search_term}%")->orWhere('p.mobile_number', 'LIKE', "%{$search_term}%")->orWhere('p.patient_id', 'LIKE', "%{$search_term}%")->orderByDesc('pmr.id')->get();

        return view('patient.search-consultation', compact('records', 'search_term'));
    }
    public function fetchmedicalrecord(Request $request){
        $this->validate($request, [
            'search_term' => 'required',
        ]);
        $input = $request->all();
        $search_term = $request->search_term;
        $records = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('doctors as doc', 'pmr.doctor_id', '=', 'doc.id')->leftJoin('patient_references as pref', 'pref.id', '=', 'pmr.mrn')->select('pmr.id', 'pmr.mrn', 'pr.patient_name', 'pr.patient_id', 'pr.mobile_number', 'doc.doctor_name', 'pmr.status', DB::Raw("DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS rdate, IFNULL(DATE_FORMAT(pmr.review_date, '%d/%b/%Y'), '--') AS review_date"))->where('pmr.id', $search_term)->orWhere('pr.patient_name', 'LIKE', "%{$search_term}%")->orWhere('pr.mobile_number', 'LIKE', "%{$search_term}%")->orWhere('pr.patient_id', 'LIKE', "%{$search_term}%")->orderByDesc('pmr.id')->get();
        return view('patient.search-medical-record', compact('records', 'search_term'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = PatientRegistrations::find($id);
        $cities = DB::table('city')->get();   
        $states = DB::table('state')->get();    
        $countries = DB::table('country')->get();
    
        return view('patient.edit',compact('patient','cities','states', 'countries'));
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
            'patient_name' => 'required',
            'gender' => 'required',
            'mobile_number' => 'required|min:10|max:10',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);
        $input = $request->all();
        $input['dob'] = (!empty($request->dob)) ? Carbon::createFromFormat('d/M/Y', $request['dob'])->format('Y-m-d') : NULL;

        $patient = PatientRegistrations::find($id);
        $input['patient_id'] = $patient->getOriginal('patient_id');
        $input['created_by'] = $patient->getOriginal('created_by');
        $input['branch'] = $patient->getOriginal('branch');
        $input['registration_fee'] = DB::table('branches')->where('id', $patient->getOriginal('branch'))->value('registration_fee');
        $patient->update($input);
        
        return redirect()->route('patient.index')->with('success','Patient updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PatientRegistrations::find($id)->delete();
        return redirect()->route('patient.index')
                        ->with('success','Patient deleted successfully');
    }
}
