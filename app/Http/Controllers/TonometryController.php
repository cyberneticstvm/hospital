<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\PatientProcedure;
use App\Models\PatientReference;
use Illuminate\Http\Request;
use App\Models\Tonometry;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class TonometryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:tonometry-list|tonometry-create|tonometry-edit|tonometry-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:tonometry-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tonometry-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tonometry-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    public function index()
    {
        $tonometries = Tonometry::withTrashed()->leftJoin('patient_medical_records AS m', 'tonometries.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'tonometries.patient_id', '=', 'p.id')->selectRaw("tonometries.*, p.patient_name, p.patient_id, tonometries.medical_record_id")->where('tonometries.branch', $this->branch)->whereDate('tonometries.created_at', Carbon::today())->orderByDesc("tonometries.id")->get();
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
            'procedure' => 'required|array|min:1',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try {
            $tonometry = Tonometry::create($input);
            if (!empty($input['procedure'])) :
                for ($i = 0; $i < count($request->procedure); $i++) :
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee[0],
                        'discount' => $fee[1],
                        'discount_category' => $fee[2],
                        'discount_category_id' => $fee[3],
                        'type' => 'T',
                        'created_by' => $request->user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('tonometry.index')->with('success', 'Record created successfully');
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
        if ($mrecord) :
            $procedures = DB::table('procedures')->where('type', 'T')->get();
            $pref = PatientReference::where('id', $mrecord->mrn)->first();
            $powers_nct = DB::table('eye_powers')->where('category', 'tonometry_nct')->get();
            $powers_at = DB::table('eye_powers')->where('category', 'tonometry_at')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('tonometry.create', compact('mrecord', 'patient', 'doctor', 'age', 'powers_nct', 'powers_at', 'procedures', 'pref'));
        else :
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
        $powers_nct = DB::table('eye_powers')->where('category', 'tonometry_nct')->orderBy('value', 'ASC')->get();
        $powers_at = DB::table('eye_powers')->where('category', 'tonometry_at')->get();
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $procedures = DB::table('procedures')->where('type', 'T')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $tonometry->medical_record_id)->where('type', 'T')->get();
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('tonometry.edit', compact('mrecord', 'patient', 'doctor', 'age', 'powers_nct', 'powers_at', 'tonometry', 'procedures', 'advised'));
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
            'procedure' => 'required|array|min:1',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        $to = Tonometry::find($id);
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'T')->delete();
            $to->update($input);
            if (!empty($input['procedure'])) :
                for ($i = 0; $i < count($request->procedure); $i++) :
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee[0],
                        'discount' => $fee[1],
                        'discount_category' => $fee[2],
                        'discount_category_id' => $fee[3],
                        'type' => 'T',
                        'created_by' => $request->user()->id,
                        'created_at' => $to->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('tonometry.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $tonometry = Tonometry::find($id);
        Tonometry::where('id', $id)->update([
            'deleted_by' => $request->user()->id,
        ]);
        PatientProcedure::where('medical_record_id', $tonometry->medical_record_id)->where('type', 'T')->delete();
        $tonometry->delete();
        return redirect()->route('tonometry.index')
            ->with('success', 'Record deleted successfully');
    }
}
