<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\Ascan;
use App\Models\PatientProcedure;
use App\Models\PatientReference;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class AscanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:ascan-list|ascan-create|ascan-edit|ascan-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:ascan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ascan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ascan-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
    }
    public function index()
    {
        $ascans = Ascan::withTrashed()->leftJoin('patient_medical_records AS m', 'ascans.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'ascans.patient_id', '=', 'p.id')->where('ascans.branch', $this->branch)->selectRaw("ascans.*, p.patient_name, p.patient_id, ascans.medical_record_id")->whereDate('ascans.created_at', Carbon::today())->orderByDesc("ascans.id")->get();
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
            'eye' => 'required',
            'procedure' => 'required|array|min:1',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try {
            $ascan = Ascan::create($input);
            if (!empty($input['procedure'])):
                for ($i = 0; $i < count($request->procedure); $i++):
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
                        'type' => 'A',
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

        return redirect()->route('ascan.index')->with('success', 'Record created successfully');
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
        if ($mrecord):
            $procedures = DB::table('procedures')->where('type', 'A')->get();
            $pref = PatientReference::where('id', $mrecord->mrn)->first();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('ascan.create', compact('mrecord', 'patient', 'doctor', 'age', 'procedures', 'pref'));
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
        $procedures = DB::table('procedures')->where('type', 'A')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $ascan->medical_record_id)->where('type', 'A')->get();
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('ascan.edit', compact('mrecord', 'patient', 'doctor', 'age', 'ascan', 'procedures', 'advised'));
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
            'eye' => 'required',
            'procedure' => 'required|array|min:1',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        $asc = Ascan::find($id);
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'A')->delete();
            $asc->update($input);
            if (!empty($input['procedure'])):
                for ($i = 0; $i < count($request->procedure); $i++):
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
                        'type' => 'A',
                        'created_by' => $request->user()->id,
                        'created_at' => $asc->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('ascan.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ascan = Ascan::find($id);
        Ascan::where('id', $id)->update([
            'deleted_by' => $request->user()->id,
        ]);
        PatientProcedure::where('medical_record_id', $ascan->medical_record_id)->where('type', 'A')->delete();
        $ascan->delete();
        return redirect()->route('ascan.index')
            ->with('success', 'Record deleted successfully');
    }
}
