<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Oct;
use App\Models\OctDocs;
use App\Models\PatientProcedure;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OCTController extends Controller
{

    private $branch;

    function __construct()
    {
        $this->middleware('permission:oct-list|oct-create|oct-edit|oct-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:oct-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:oct-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:oct-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $octs = Oct::leftJoin('patient_medical_records AS m', 'octs.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'octs.patient_id', '=', 'p.id')->selectRaw("octs.*, p.patient_name, p.patient_id, octs.medical_record_id")->when(Auth::user()->roles->first()->name == 'Doctor', function ($q) {
            return $q->where('m.doctor_id', Auth::user()->doctor_id);
        })->where('octs.branch_id', $this->branch)->orderByDesc("octs.id")->get();
        return view('oct.index', compact('octs'));
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
            'images.*' => 'required|mimes:jpg,jpeg,png,webp',
            'documents.*' => [
                'sometimes',
                'mimes:pdf',
            ],
        ]);
        try {
            $input = $request->except(array('images', 'documents', 'procedures'));
            $input1 = $request->only(array('procedures'));
            $input['created_by'] = $request->user()->id;
            $input['updated_by'] = $request->user()->id;
            $input['receipt_number'] = Oct::selectRaw("IFNULL(MAX(receipt_number) + 1, 1) AS rmax")->first()->rmax;
            DB::transaction(function () use ($input, $input1, $request) {
                $oct = Oct::create($input);
                if ($request->file('images')):
                    $images = $request->file('images');
                    $fpath = 'oct/' . $request->medical_record_id;
                    foreach ($images as $key => $image):
                        $url = Storage::disk('public')->putFile($fpath, $image);
                        OctDocs::insert([
                            'oct_id' => $oct->id,
                            'doc_type' => 'img',
                            'doc_url' => $url,
                        ]);
                    endforeach;
                endif;
                if ($request->file('documents')):
                    $documents = $request->file('documents');
                    $fpath = 'oct/' . $request->medical_record_id;
                    foreach ($documents as $key => $doc):
                        $url = Storage::disk('public')->putFile($fpath, $doc);
                        OctDocs::insert([
                            'oct_id' => $oct->id,
                            'doc_type' => 'pdf',
                            'doc_url' => $url,
                        ]);
                    endforeach;
                endif;
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
                            'type' => 'O',
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
        return redirect()->route('oct.index')->with('success', 'Record created successfully');
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
            $procedures = DB::table('procedures')->where('type', 'O')->get();
            $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
            return view('oct.create', compact('mrecord', 'patient', 'procedures'));
        else :
            return redirect()->route('oct.index')->withErrors('No records found.');
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
        $oct = Oct::find($id);
        $mrecord = DB::table('patient_medical_records')->find($oct->medical_record_id);
        $patient = DB::table('patient_registrations')->find($mrecord->patient_id);
        $procedures = DB::table('procedures')->where('type', 'O')->get();
        $advised = DB::table('patient_procedures')->where('medical_record_id', $oct->medical_record_id)->where('type', 'O')->get();
        $docs = OctDocs::where('oct_id', $oct->id)->get();
        return view('oct.edit', compact('mrecord', 'patient', 'oct', 'procedures', 'advised', 'docs'));
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
            'images.*' => 'required|mimes:jpg,jpeg,png,webp',
            'documents.*' => [
                'sometimes',
                'mimes:pdf',
            ],
        ]);
        try {

            $input = $request->except(array('images', 'documents', 'procedures'));
            $input1 = $request->only(array('procedures'));
            $input['updated_by'] = $request->user()->id;
            DB::transaction(function () use ($id, $input, $input1, $request) {
                $oct = Oct::findOrFail($id);
                $oct->update($input);
                if ($request->file('images')):
                    $images = $request->file('images');
                    $fpath = 'oct/' . $request->medical_record_id;
                    foreach ($images as $key => $image):
                        $url = Storage::disk('public')->putFile($fpath, $image);
                        OctDocs::insert([
                            'oct_id' => $oct->id,
                            'doc_type' => 'img',
                            'doc_url' => $url,
                        ]);
                    endforeach;
                endif;
                if ($request->file('documents')):
                    $documents = $request->file('documents');
                    $fpath = 'oct/' . $request->medical_record_id;
                    foreach ($documents as $key => $doc):
                        $url = Storage::disk('public')->putFile($fpath, $doc);
                        OctDocs::insert([
                            'oct_id' => $oct->id,
                            'doc_type' => 'pdf',
                            'doc_url' => $url,
                        ]);
                    endforeach;
                endif;
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
                            'type' => 'O',
                            'created_by' => $request->user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    endfor;
                    PatientProcedure::where('medical_record_id', $request->medical_record_id)->where('type', 'O')->where('patient_id', $request->patient_id)->delete();
                    DB::table('patient_procedures')->insert($data);
                endif;
            });
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage())->withInput($request->all());
        }
        return redirect()->route('oct.index')->with('success', 'Record created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oct = Oct::find($id);
        DB::table('patient_procedures')->where('medical_record_id', $oct->medical_record_id)->where('type', 'O')->delete();
        OctDocs::where('oct_id', $oct->id)->delete();
        $oct->delete();
        return redirect()->route('oct.index')
            ->with('success', 'Record deleted successfully');
    }
}
