<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surgery;
use Carbon\Carbon;
use DB;

class PostOperativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:postop-list|postop-create|postop-edit|postop-delete', ['only' => ['index','store']]);
        $this->middleware('permission:postop-create', ['only' => ['create','store']]);
        $this->middleware('permission:postop-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:postop-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
   }

    public function index()
    {
        $surgeries = DB::table('surgeries as s')->leftJoin('patient_registrations as p', 's.patient_id', '=', 'p.id')->leftJoin('doctors as d', 's.doctor_id', '=', 'd.id')->leftJoin('patient_medical_records as pmr', 'pmr.id', '=', 's.medical_record_id')->leftJoin('surgery_types as st', 's.surgery_type', '=', 'st.id')->leftjoin('doctors as doc', 's.surgeon', '=', 'doc.id')->leftJoin('types as t', 't.id', '=', 's.status')->whereIn('s.status', [4])->selectRaw("s.id, p.id as pid, p.patient_name, p.mobile_number, p.patient_id, d.doctor_name, s.medical_record_id, DATE_FORMAT(s.surgery_date, '%d/%b/%Y') AS sdate, CASE WHEN pmr.is_patient_surgery = 'N' THEN 'No' ELSE 'Yes' END AS is_patient_surgery, st.surgery_name, doc.doctor_name as surgeon, s.eye, s.remarks, t.name as sname")->orderByRaw('ISNULL(s.surgery_date), s.surgery_date ASC')->get();
        return view('surgery.post-op-index', compact('surgeries'));
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
        //
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
        //
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
