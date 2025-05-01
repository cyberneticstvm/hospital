<?php

namespace App\Http\Controllers;

use App\Models\PatientAcknoledgement;
use App\Models\PatientAcknowledgementProcedure;
use App\Models\PatientReference;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientAcknoledgementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:patient-acknowledgement-list|patient-acknowledgement-create|patient-acknowledgement-edit|patient-acknowledgement-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:patient-acknowledgement-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-acknowledgement-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:patient-acknowledgement-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $acknowledgements = PatientAcknoledgement::withTrashed()->latest()->get();
        return view('acknowledgement.index', compact('acknowledgements'));
    }

    public function fetch(Request $request)
    {
        $this->validate($request, [
            'medical_record_id' => 'required',
        ]);
        $pref = PatientReference::find($request->medical_record_id);
        if ($pref):
            $procs = DB::table('types')->where('category', 'ack')->get();
            return view('acknowledgement.create', compact('pref', 'procs'));
        else:
            return redirect()->back()->withErrors("No records found");
        endif;
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
            'procs' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request) {
                $pa = PatientAcknoledgement::create([
                    'medical_record_id' => $request->medical_record_id,
                    'patient_id' => $request->patient_id,
                    'branch_id' => $request->branch_id,
                    'notes' => $request->notes,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $procs = [];
                foreach ($request->procs as $key => $item):
                    $procs[] = [
                        'patient_acknowledgement_id' => $pa->id,
                        'procedure_id' => $item,
                    ];
                endforeach;
                PatientAcknowledgementProcedure::insert($procs);
            });
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput($request->all());
        }
        return redirect()->back()->with("success", "Acknowledgement Updated Successfully");
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
        $ack = PatientAcknoledgement::find($id);
        $procs = DB::table('types')->where('category', 'ack')->get();
        $ackproc = PatientAcknowledgementProcedure::where('patient_acknowledgement_id', $id)->pluck('procedure_id')->all();
        return view('acknowledgement.edit', compact('ack', 'procs', 'ackproc'));
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
            'procs' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request, $id) {
                PatientAcknoledgement::find($id)->update([
                    'medical_record_id' => $request->medical_record_id,
                    'patient_id' => $request->patient_id,
                    'branch_id' => $request->branch_id,
                    'notes' => $request->notes,
                    'updated_by' => $request->user()->id,
                    'updated_at' => Carbon::now(),
                ]);
                $procs = [];
                foreach ($request->procs as $key => $item):
                    $procs[] = [
                        'patient_acknowledgement_id' => $id,
                        'procedure_id' => $item,
                    ];
                endforeach;
                PatientAcknowledgementProcedure::where('patient_acknowledgement_id', $id)->delete();
                PatientAcknowledgementProcedure::insert($procs);
            });
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput($request->all());
        }
        return redirect()->route('patient.ack.list')->with("success", "Acknowledgement Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PatientAcknoledgement::find($id)->delete();
        //PatientAcknowledgementProcedure::where('patient_acknowledgement_id', $id)->delete();
        return redirect()->route('patient.ack.list')->with("success", "Acknowledgement Deleted Successfully");
    }
}
