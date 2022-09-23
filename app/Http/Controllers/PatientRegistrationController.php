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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$patients = PatientRegistrations::orderByDesc('id')->get();
        $patients = DB::table('patient_registrations')->select('*', DB::Raw("DATE_FORMAT(created_at, '%d/%b/%Y') AS rdate"))->get();
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
