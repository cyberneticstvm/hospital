<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procedure;
use Carbon\Carbon;
use DB;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
         $this->middleware('permission:procedure-list|procedure-create|procedure-edit|procedure-delete', ['only' => ['index','store']]);
         $this->middleware('permission:procedure-create', ['only' => ['create','store']]);
         $this->middleware('permission:procedure-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:procedure-delete', ['only' => ['destroy']]);

         $this->middleware('permission:procedure-advise-list|procedure-advise-create|procedure-advise-edit|procedure-advise-delete', ['only' => ['fetch','saveadvise']]);
         $this->middleware('permission:procedure-advise-create', ['only' => ['show','saveadvise']]);
         $this->middleware('permission:procedure-advise-edit', ['only' => ['editadvise','updateadvise']]);
         $this->middleware('permission:procedure-advise-delete', ['only' => ['destroyadvise']]);

         $this->branch = session()->get('branch');
    }

    public function index()
    {
        $procedures = Procedure::orderBy('name', 'ASC')->get();
        $proc = [];
        return view('procedure.index', compact('procedures', 'proc'));
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
            'name' => 'required|unique:procedures,name',
            'fee' => 'required',
        ]);
        $input = $request->all();
        $input['type'] = 'P';
        $proc = Procedure::create($input);
        return redirect()->route('procedure.index')
                        ->with('success','Procedure created successfully');
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
            $procedures = Procedure::where('type', 'P')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
            return view('procedure.create', compact('mrecord', 'patient', 'doctor', 'age', 'procedures'));
        else:
            return redirect("/consultation/procedure/")->withErrors('No records found.');
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
        $procedures = Procedure::all();
        $proc = Procedure::find($id);
        return view('procedure.index', compact('proc', 'procedures'));
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
            'name' => 'required|unique:procedures,name,'.$id,
            'fee' => 'required',
        ]);
        $input = $request->all();
        $input['type'] = 'P';
        $proc = Procedure::find($id);
        $proc->update($input);
        return redirect()->route('procedure.index')
                        ->with('success','Procedure updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Procedure::find($id)->delete();
        return redirect()->route('procedure.index')
                        ->with('success','Procedure deleted successfully');
    }

    public function fetch(){
        $procedures = Procedure::where('type', 'P')->get();
        $procs = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 'pp.medical_record_id')->leftJoin('patient_registrations as pr', 'pr.id', '=', 'pmr.patient_id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id', 'pr.patient_name', 'pr.patient_id', DB::raw("SUM(pp.fee) as 'fee'"))->whereNull('pp.type')->where('pp.branch', $this->branch)->whereDate('pp.created_at', Carbon::today())->groupBy('pp.medical_record_id')->orderByDesc('pp.id')->get();
        return view('procedure.fetch', compact('procedures', 'procs'));
    }

    public function saveadvise(Request $request){
        $this->validate($request, [
            'procedure' => 'required',
        ]);
        $input = $request->all();
        try{
            if($input['procedure']):
                for($i=0; $i<count($input['procedure']); $i++):
                    if($input['procedure'][$i] > 0):
                        $proc = Procedure::find($input['procedure'][$i]);
                        DB::table('patient_procedures')->insert([
                            'medical_record_id' => $request->medical_record_id,
                            'patient_id' => $request->patient_id,
                            'branch' => $this->branch,
                            'procedure' => $input['procedure'][$i],
                            'fee' => $proc->fee,
                            'created_by' => $request->user()->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    endif;
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        $procedures = Procedure::where('type', 'P')->get();
        $procs = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id', DB::raw("SUM(pp.fee) as 'fee'"))->groupBy('pp.medical_record_id')->get();
        return redirect()->route('procedure.fetch', compact('procs', 'procedures'))
                        ->with('success','Procedure created successfully');
    }

    public function editadvise($id){
        $mrecord = DB::table('patient_medical_records')->find($id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $age = DB::table('patient_registrations')->where('id', $mrecord->patient_id)->selectRaw('CASE WHEN age > 0 THEN age+(YEAR(NOW())-YEAR(created_at)) ELSE timestampdiff(YEAR, dob, NOW()) END AS age')->pluck('age')->first();
        $procedures = Procedure::where('type', 'P')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $id)->get();
        return view('procedure.edit', compact('mrecord', 'patient', 'doctor', 'age', 'procedures', 'advised'));
    }

    public function updateadvise(Request $request, $id){
        $this->validate($request, [
            'procedure' => 'required',
        ]);
        $input = $request->all();
        try{
            DB::table('patient_procedures')->where('medical_record_id', $id)->delete();
            if($input['procedure']):
                for($i=0; $i<count($input['procedure']); $i++):
                    if($input['procedure'][$i] > 0):
                        $proc = Procedure::find($input['procedure'][$i]);
                        DB::table('patient_procedures')->insert([
                            'medical_record_id' => $request->medical_record_id,
                            'patient_id' => $request->patient_id,
                            'branch' => $this->branch,
                            'procedure' => $input['procedure'][$i],
                            'fee' => $proc->fee,
                            'created_by' => $request->user()->id,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    endif;
                endfor;
            endif;
        }catch(Exception $e){
            throw $e;
        }
        $procedures = Procedure::where('type', 'P')->get();
        $procs = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id', DB::raw("SUM(pp.fee) as 'fee'"))->groupBy('pp.medical_record_id')->get();
        return redirect()->route('procedure.fetch', compact('procs', 'procedures'))
                        ->with('success','Procedure updated successfully');
    }

    public function destroyadvise($id){
        DB::table('patient_procedures')->where('medical_record_id', $id)->delete();
        $procedures = Procedure::where('type', 'P')->get();
        $procs = DB::table('patient_procedures as pp')->leftJoin('procedures as p', 'pp.procedure', '=', 'p.id')->select(DB::raw("(GROUP_CONCAT(p.name SEPARATOR ',')) as 'procs'"), 'pp.medical_record_id', DB::raw("SUM(pp.fee) as 'fee'"))->groupBy('pp.medical_record_id')->get();
        return redirect()->route('procedure.fetch', compact('procs', 'procedures'))
                        ->with('success','Procedure deleted successfully');
    }
}
