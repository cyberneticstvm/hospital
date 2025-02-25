<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\Keratometry;
use App\Models\PatientProcedure;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class KeratometryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:keratometry-list|keratometry-create|keratometry-edit|keratometry-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:keratometry-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:keratometry-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:keratometry-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $keratometries = Keratometry::withTrashed()->leftJoin('patient_medical_records AS m', 'keratometries.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'keratometries.patient_id', '=', 'p.id')->selectRaw("keratometries.*, p.patient_name, p.patient_id, keratometries.medical_record_id")->where('keratometries.branch', $this->branch)->whereDate('keratometries.created_at', Carbon::today())->orderByDesc("keratometries.id")->get();
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
            'procedure' => 'required|array|min:1',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try {
            $keratometry = Keratometry::create($input);
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
                        'type' => 'K',
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
        return redirect()->route('keratometry.index')->with('success', 'Record created successfully');
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
            $procedures = DB::table('procedures')->where('type', 'K')->get();
            $powers = DB::table('eye_powers')->where('category', 'keratometry')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('keratometry.create', compact('mrecord', 'patient', 'doctor', 'age', 'powers', 'procedures'));
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
        $procedures = DB::table('procedures')->where('type', 'K')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $keratometry->medical_record_id)->where('type', 'K')->get();
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        return view('keratometry.edit', compact('mrecord', 'patient', 'doctor', 'age', 'powers', 'keratometry', 'procedures', 'advised'));
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
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'K')->delete();
            $ke = Keratometry::find($id);
            $ke->update($input);
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
                        'type' => 'K',
                        'created_by' => $request->user()->id,
                        'created_at' => $ke->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('keratometry.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ke = Keratometry::find($id);
        $ke->update([
            'deleted_by' => Auth::user()->id,
        ]);
        PatientProcedure::where('medical_record_id', $ke->medical_record_id)->where('type', 'K')->delete();
        $ke->delete();
        return redirect()->route('keratometry.index')
            ->with('success', 'Record deleted successfully');
    }
}
