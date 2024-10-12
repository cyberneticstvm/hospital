<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\Pachymetry;
use Carbon\Carbon;
use DB;


class PachymetryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:pachymetry-list|pachymetry-create|pachymetry-edit|pachymetry-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:pachymetry-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pachymetry-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pachymetry-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $pams = Pachymetry::leftJoin('patient_medical_records AS m', 'pachymetries.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'pachymetries.patient_id', '=', 'p.id')->selectRaw("pachymetries.*, p.patient_name, p.patient_id, pachymetries.medical_record_id")->where('pachymetries.branch', $this->branch)->whereDate('pachymetries.created_at', Carbon::today())->orderByDesc("pachymetries.id")->get();
        return view('pachymetry.index', compact('pams'));
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
        if ($request->img1):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img1'] = Storage::disk('public')->putFile($fpath, $request->img1);
        endif;
        if ($request->img2):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img2'] = Storage::disk('public')->putFile($fpath, $request->img2);
        endif;
        if ($request->img3):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img3'] = Storage::disk('public')->putFile($fpath, $request->img3);
        endif;
        if ($request->img4):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img4'] = Storage::disk('public')->putFile($fpath, $request->img4);
        endif;
        try {
            $pachymetry = Pachymetry::create($input);
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
                        'type' => 'P',
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
        return redirect()->route('pachymetry.index')->with('success', 'Record created successfully');
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
            $procedures = DB::table('procedures')->where('type', 'C')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            $doctor = DB::table('doctors')->find($mrecord->doctor_id);
            return view('pachymetry.create', compact('mrecord', 'patient', 'doctor', 'procedures'));
        else:
            return redirect("/pachymetry/")->withErrors('No records found.');
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
        $pachymetry = Pachymetry::find($id);
        $mrecord = DB::table('patient_medical_records')->find($pachymetry->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $doctor = DB::table('doctors')->find($mrecord->doctor_id);
        $procedures = DB::table('procedures')->where('type', 'C')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $pachymetry->medical_record_id)->where('type', 'P')->get();
        return view('pachymetry.edit', compact('mrecord', 'patient', 'doctor', 'pachymetry', 'procedures', 'advised'));
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
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        if ($request->img1):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img1'] = Storage::disk('public')->putFile($fpath, $request->img1);
        endif;
        if ($request->img2):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img2'] = Storage::disk('public')->putFile($fpath, $request->img2);
        endif;
        if ($request->img3):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img3'] = Storage::disk('public')->putFile($fpath, $request->img3);
        endif;
        if ($request->img4):
            $fpath = 'assets/pachymetry/' . $request->medical_record_id;
            $input['img4'] = Storage::disk('public')->putFile($fpath, $request->img4);
        endif;
        try {
            DB::table('patient_procedures')->where('medical_record_id', $request->medical_record_id)->where('type', 'P')->delete();
            $p = Pachymetry::find($id);
            $p->update($input);
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
                        'type' => 'P',
                        'created_by' => $request->user()->id,
                        'created_at' => $p->created_at,
                        'updated_at' => Carbon::now(),
                    ];
                endfor;
                DB::table('patient_procedures')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('pachymetry.index')->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p = Pachymetry::find($id);
        DB::table('patient_procedures')->where('medical_record_id', $p->medical_record_id)->where('type', 'P')->delete();
        $p->delete();
        return redirect()->route('pachymetry.index')
            ->with('success', 'Record deleted successfully');
    }
}
