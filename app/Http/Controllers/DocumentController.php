<?php

namespace App\Http\Controllers;

use App\Models\Bscan;
use App\Models\BscanDocs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Oct;
use App\Models\OctDocs;
use DB;

class DocumentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:document-list|document-create|document-edit|document-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:document-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:document-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patient = [];
        $doctor = [];
        $mref = [];
        $docs = [];
        $octs = collect();
        return view('documents.index', compact('docs', 'mref', 'doctor', 'patient', 'octs'));
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
            'doc' => 'required',
            'description' => 'required',
        ]);
        $input = $request->all();
        if ($request->hasFile('doc')):
            $doc = $request->file('doc');
            $fname = 'patient/' . $request->medical_record_id . '/' . $doc->getClientOriginalName();
            Storage::disk('public')->putFileAs($fname, $doc, '');
            $input['name'] = $doc->getClientOriginalName();
            $input['type'] = $doc->extension();
        endif;
        $input['created_by'] = Auth::user()->id;
        Document::create($input);
        return redirect()->route('documents.edit', $request->medical_record_id)->with('success', 'Document Uploaded successfully!');
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
        $mref = DB::table('patient_references')->find($request->medical_record_number);
        if ($mref):
            $patient = DB::table('patient_registrations')->find($mref->patient_id);
            $doctor = DB::table('doctors')->find($mref->doctor_id);
            $docs = DB::table('documents')->where('medical_record_id', $mref->id)->get();
            $octs = OctDocs::whereIn('oct_id', Oct::where('medical_record_id', $mref->id)->pluck('id'))->get();
            $bscans = BscanDocs::whereIn('bscan_id', Bscan::where('medical_record_id', $mref->id)->pluck('id'))->get();
            return view('documents.index', compact('docs', 'mref', 'doctor', 'patient', 'octs', 'bscans'));
        else:
            return redirect()->route('documents.index')->withErrors('No records found!');
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
        $mref = DB::table('patient_references')->find($id);
        $patient = DB::table('patient_registrations')->find($mref->patient_id);
        $doctor = DB::table('doctors')->find($mref->doctor_id);
        $docs = DB::table('documents')->where('medical_record_id', $mref->id)->get();
        $octs = OctDocs::whereIn('oct_id', Oct::where('medical_record_id', $mref->id)->pluck('id'))->get();
        $bscans = BscanDocs::whereIn('bscan_id', Bscan::where('medical_record_id', $mref->id)->pluck('id'))->get();
        return view('documents.view', compact('docs', 'mref', 'doctor', 'patient', 'octs', 'bscans'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
