<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Laser;
use App\Models\PatientProcedure;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaserController extends Controller
{
    private $branch;

    function __construct()
    {
        $this->middleware('permission:laser-list|laser-create|laser-edit|laser-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:laser-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:laser-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:laser-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lasers = Laser::withTrashed()->leftJoin('patient_medical_records AS m', 'lasers.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'lasers.patient_id', '=', 'p.id')->selectRaw("lasers.*, p.patient_name, p.patient_id, lasers.medical_record_id")->when(Auth::user()->roles->first()->name == 'Doctor', function ($q) {
            return $q->where('m.doctor_id', Auth::user()->doctor_id);
        })->where('lasers.branch_id', $this->branch)->latest()->get();
        return view('laser.index', compact('lasers'));
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
            'procedures.*' => 'required',
        ]);
        try {
            $input = $request->except(array('procedures'));
            $input1 = $request->only(array('procedures'));
            $input['created_by'] = $request->user()->id;
            $input['updated_by'] = $request->user()->id;
            $input['receipt_number'] = Laser::selectRaw("IFNULL(MAX(receipt_number) + 1, 1) AS rmax")->first()->rmax;
            DB::transaction(function () use ($input, $input1, $request) {
                $bscan = Laser::create($input);
                if (!empty($input1['procedures'])) :
                    for ($i = 0; $i < count($request->procedures); $i++) :
                        $fee = Helper::getProcedureFee($request->medical_record_id, $input1['procedures'][$i]);
                        $data[] = [
                            'medical_record_id' => $request->medical_record_id,
                            'patient_id' => $request->patient_id,
                            'branch' => $request->branch_id,
                            'procedure' => $input1['procedures'][$i],
                            'fee' => $fee[0],
                            'discount' => $fee[1],
                            'discount_category' => $fee[2],
                            'discount_category_id' => $fee[3],
                            'type' => 'G',
                            'created_by' => $request->user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    endfor;
                    DB::table('patient_procedures')->insert($data);
                endif;
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('laser.index')->with('success', 'Record created successfully');
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
            $procedures = DB::table('procedures')->where('type', 'G')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            return view('laser.create', compact('mrecord', 'patient', 'procedures'));
        else :
            return redirect()->route('laser.index')->withErrors('No records found.');
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
        $laser = Laser::find($id);
        $mrecord = DB::table('patient_medical_records')->find($laser->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $procedures = DB::table('procedures')->where('type', 'G')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $laser->medical_record_id)->where('type', 'G')->get();
        return view('laser.edit', compact('mrecord', 'patient', 'laser', 'procedures', 'advised'));
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
            'procedures.*' => 'required',
        ]);
        try {

            $input = $request->except(array('procedures'));
            $input1 = $request->only(array('procedures'));
            $input['updated_by'] = $request->user()->id;
            DB::transaction(function () use ($id, $input, $input1, $request) {
                $laser = Laser::findOrFail($id);
                $laser->update($input);
                if (!empty($input1['procedures'])) :
                    for ($i = 0; $i < count($request->procedures); $i++) :
                        $fee = Helper::getProcedureFee($request->medical_record_id, $input1['procedures'][$i]);
                        $data[] = [
                            'medical_record_id' => $request->medical_record_id,
                            'patient_id' => $request->patient_id,
                            'branch' => $request->branch_id,
                            'procedure' => $input1['procedures'][$i],
                            'fee' => $fee[0],
                            'discount' => $fee[1],
                            'discount_category' => $fee[2],
                            'discount_category_id' => $fee[3],
                            'type' => 'G',
                            'created_by' => $request->user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    endfor;
                    PatientProcedure::where('medical_record_id', $request->medical_record_id)->where('type', 'G')->where('patient_id', $request->patient_id)->delete();
                    DB::table('patient_procedures')->insert($data);
                endif;
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('laser.index')->with('success', 'Record created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $laser = Laser::find($id);
        Laser::where('id', $id)->update([
            'deleted_by' => $request->user()->id,
        ]);
        PatientProcedure::where('medical_record_id', $laser->medical_record_id)->where('type', 'G')->delete();
        $laser->delete();
        return redirect()->route('laser.index')
            ->with('success', 'Record deleted successfully');
    }
}
