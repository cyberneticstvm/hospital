<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\AxialLength;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AxialLengthController extends Controller
{
    private $branch;
    function __construct()
    {
        $this->middleware('permission:axial-length-list|axial-length-create|axial-length-edit|axial-length-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:axial-length-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:axial-length-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:axial-length-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $axs = AxialLength::leftJoin('patient_medical_records AS m', 'axial_lengths.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'axial_lengths.patient_id', '=', 'p.id')->selectRaw("axial_lengths.*, p.patient_name, p.patient_id, axial_lengths.medical_record_id")->where('axial_lengths.branch_id', $this->branch)->whereDate('axial_lengths.created_at', Carbon::today())->orderByDesc("axial_lengths.id")->get();
        return view('axial-length.index', compact('axs'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try {
            $ax = AxialLength::create($input);
            if (!empty($input['procedure'])):
                for ($i = 0; $i < count($request->procedure); $i++):
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch_id,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee[0],
                        'discount' => $fee[1],
                        'discount_category' => $fee[2],
                        'discount_category_id' => $fee[3],
                        'type' => 'L',
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
        return redirect()->route('procedure.axial.length')->with('success', 'Record created successfully');
    }

    public function show(Request $request)
    {
        $this->validate($request, [
            'medical_record_id' => 'required',
        ]);
        $mrecord = DB::table('patient_medical_records')->find($request->medical_record_id);
        if ($mrecord):
            $procedures = DB::table('procedures')->where('type', 'L')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            return view('axial-length.create', compact('mrecord', 'patient', 'doctor', 'procedures'));
        else:
            return redirect()->back()->withErrors('No records found.');
        endif;
    }

    public function edit($id)
    {
        $ax = AxialLength::find($id);
        $mrecord = DB::table('patient_medical_records')->find($ax->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $procedures = DB::table('procedures')->where('type', 'L')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $ax->medical_record_id)->where('type', 'L')->get();
        return view('axial-length.edit', compact('mrecord', 'patient', 'doctor', 'ax', 'procedures', 'advised'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'L')->delete();
            $ax = AxialLength::find($id);
            $ax->update($input);
            if (!empty($input['procedure'])):
                for ($i = 0; $i < count($request->procedure); $i++):
                    $fee = Helper::getProcedureFee($request->medical_record_id, $input['procedure'][$i]);
                    $data[] = [
                        'medical_record_id' => $request->medical_record_id,
                        'patient_id' => $request->patient_id,
                        'branch' => $request->branch_id,
                        'procedure' => $input['procedure'][$i],
                        'fee' => $fee[0],
                        'discount' => $fee[1],
                        'discount_category' => $fee[2],
                        'discount_category_id' => $fee[3],
                        'type' => 'P',
                        'created_by' => $request->user()->id,
                        'created_at' => $ax->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('procedure.axial.length')->with('success', 'Record updated successfully');
    }

    public function destroy($id)
    {
        $ax = AxialLength::find($id);
        DB::table('patient_procedures')->where('medical_record_id', $ax->medical_record_id)->where('type', 'L')->delete();
        $ax->delete();
        return redirect()->route('procedure.axial.length')
            ->with('success', 'Record deleted successfully');
    }
}
