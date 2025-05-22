<?php

namespace App\Http\Controllers;

use App\Models\Ascan;
use App\Models\doctor;
use Illuminate\Http\Request;
use App\Models\OperationNote;
use App\Models\Surgery;
use Carbon\Carbon;
use DB;


class OperationNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:operation-note-list|operation-note-create|operation-note-edit|operation-note-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:operation-note-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:operation-note-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:operation-note-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $onotes = OperationNote::leftJoin('patient_medical_records AS m', 'operation_notes.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'operation_notes.patient_id', '=', 'p.id')->leftJoin('branches as b', 'b.id', '=', 'm.branch')->select('operation_notes.id', 'm.id as medical_record_id', 'b.branch_name', 'operation_notes.notes', 'p.patient_name', 'p.patient_id')->orderByDesc('operation_notes.id')->get();
        return view('operation-notes.index', compact('onotes'));
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
            'notes' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try {
            OperationNote::create($input);
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('onote.index')->with('success', 'Record created successfully');
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
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $branch = DB::table('branches')->find($mrecord->branch);
            $surgery = Surgery::where('medical_record_id', $request->medical_record_id)->first();
            $doctors = doctor::when($surgery?->surgeon > 0, function ($q) use ($surgery) {
                return $q->where('id', $surgery->surgeon);
            })->get();
            $ascan = Ascan::where('medical_record_id', $request->medical_record_id)->first();
            return view('operation-notes.create', compact('mrecord', 'patient', 'branch', 'doctors', 'surgery', 'ascan'));
        else:
            return redirect("/operation-notes/")->withErrors('No records found.');
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
        $onote = OperationNote::find($id);
        $mrecord = DB::table('patient_medical_records')->find($onote->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $branch = DB::table('branches')->find($mrecord->branch);
        $surgery = Surgery::where('medical_record_id', $onote->medical_record_id)->first();
        $doctors = doctor::get();
        return view('operation-notes.edit', compact('onote', 'patient', 'mrecord', 'branch', 'doctors', 'surgery'));
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
            'notes' => 'required',
            'eye' => 'required',
            'surgeon' => 'required',
            'date_of_surgery' => 'required|date',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        try {
            $onote = OperationNote::find($id);
            $onote->update($input);
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('onote.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OperationNote::find($id)->delete();
        return redirect()->route('onote.index')
            ->with('success', 'Record deleted successfully');
    }
}
