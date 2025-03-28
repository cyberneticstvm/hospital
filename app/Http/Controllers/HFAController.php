<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use App\Models\doctor;
use App\Models\HFA;
use App\Models\PatientProcedure;
use App\Models\PatientReference;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class HFAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:hfa-list|hfa-create|hfa-edit|hfa-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:hfa-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:hfa-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hfa-delete', ['only' => ['destroy']]);
        $this->middleware('permission:hfa-review-view', ['only' => ['review']]);
        $this->middleware('permission:hfa-completed-view', ['only' => ['completed']]);
        $this->middleware('permission:hfa-direct-view', ['only' => ['direct']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $hfas = HFA::withTrashed()->leftJoin('patient_medical_records AS m', 'h_f_a_s.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'h_f_a_s.patient_id', '=', 'p.id')->selectRaw("h_f_a_s.*, p.patient_name, p.patient_id, h_f_a_s.medical_record_id")->when(Auth::user()->roles->first()->name == 'Doctor', function ($q) {
            return $q->where('m.doctor_id', Auth::user()->doctor_id);
        })->where('h_f_a_s.branch', $this->branch)->whereIn('h_f_a_s.status', [1, 2])->orderByDesc("h_f_a_s.id")->get();
        $doctors = doctor::all();
        return view('hfa.index', compact('hfas', 'doctors'));
    }

    public function review()
    {
        $from_date = Carbon::now()->subDays(180)->startOfDay();
        $to_date = Carbon::now()->subDays(150)->endOfDay();
        $hfas = HFA::leftJoin('patient_medical_records AS m', 'h_f_a_s.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'h_f_a_s.patient_id', '=', 'p.id')->selectRaw("h_f_a_s.*, p.patient_name, p.patient_id, h_f_a_s.medical_record_id")->where('h_f_a_s.branch', $this->branch)->orderByDesc("h_f_a_s.id")->where('h_f_a_s.status', 4)->whereBetween('h_f_a_s.created_at', [$from_date, $to_date])->latest()->get();
        return view('hfa.review', compact('hfas'));
    }

    public function direct()
    {
        $doctors = doctor::all();
        $hfas = HFA::leftJoin('patient_medical_records AS m', 'h_f_a_s.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'h_f_a_s.patient_id', '=', 'p.id')->leftJoin('doctors as d', 'd.id', 'm.doctor_id')->selectRaw("h_f_a_s.*, p.patient_name, p.patient_id, h_f_a_s.medical_record_id")->where('h_f_a_s.branch', $this->branch)->when(Auth::user()->roles->first()->id > 0, function ($q) {
            return $q->where('d.id', 9);
        })->orderByDesc("h_f_a_s.id")->latest()->get();
        return view('hfa.direct', compact('hfas', 'doctors'));
    }

    public function completed()
    {
        $doctors = doctor::all();
        $hfas = HFA::leftJoin('patient_medical_records AS m', 'h_f_a_s.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'h_f_a_s.patient_id', '=', 'p.id')->leftJoin('doctors as d', 'd.id', 'm.doctor_id')->selectRaw("h_f_a_s.*, p.patient_name, p.patient_id, h_f_a_s.medical_record_id")->where('h_f_a_s.branch', $this->branch)->where('h_f_a_s.status', 4)->when(Auth::user()->roles->first()->name != 'Admin', function ($q) {
            return $q->where('d.id', Auth::user()->doctor_id);
        })->orderByDesc("h_f_a_s.id")->latest()->get();
        return view('hfa.direct', compact('hfas', 'doctors'));
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
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        if ($request->document) :
            $fpath = 'hfa/' . $request->medical_record_id;
            $input['document'] = Storage::disk('public')->putFile($fpath, $request->document);
        endif;
        try {
            $hfa = HFA::create($input);
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
                        'type' => 'H',
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
        return redirect()->route('hfa.index')->with('success', 'Record created successfully');
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
            $pref = PatientReference::where('id', $mrecord->mrn)->first();
            $procedures = DB::table('procedures')->where('type', 'H')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            return view('hfa.create', compact('mrecord', 'patient', 'doctor', 'procedures', 'pref'));
        else :
            return redirect("/hfa")->withErrors('No records found.');
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
        $hfa = HFA::find($id);
        $mrecord = DB::table('patient_medical_records')->find($hfa->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $procedures = DB::table('procedures')->where('type', 'H')->get();
        $status = DB::table('types')->where('category', 'surgery')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $hfa->medical_record_id)->where('type', 'H')->get();
        return view('hfa.edit', compact('mrecord', 'patient', 'doctor', 'hfa', 'procedures', 'advised', 'status'));
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
            'status' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        if ($request->document) :
            $fpath = 'hfa/' . $request->medical_record_id;
            $input['document'] = Storage::disk('public')->putFile($fpath, $request->document);
        endif;
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'H')->delete();
            $hfa = HFA::find($id);
            $hfa->update($input);
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
                        'type' => 'H',
                        'created_by' => $request->user()->id,
                        'created_at' => $hfa->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('hfa.index')->with('success', 'Record created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $hfa = HFA::find($id);
        HFA::where('id', $id)->update([
            'deleted_by' => $request->user()->id,
        ]);
        PatientProcedure::where('medical_record_id', $hfa->medical_record_id)->where('type', 'H')->delete();
        $hfa->delete();
        return redirect()->route('hfa.index')
            ->with('success', 'Record deleted successfully');
    }
}
