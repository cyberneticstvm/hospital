<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PatientCertificate as PC;
use Carbon\Carbon;
use DB;

class PatientCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()    {
        $this->middleware('permission:certificate-list|certificate-create|certificate-edit|certificate-delete', ['only' => ['index','store']]);
        $this->middleware('permission:certificate-create', ['only' => ['create','store']]);
        $this->middleware('permission:certificate-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:certificate-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
   }
    public function index()
    {
        $records = DB::table('patient_certificates as pc')->leftJoin('patient_registrations as pr', 'pc.patient_id', '=', 'pr.id')->leftJoin('branches as b', 'b.id', '=', 'pc.branch_id')->leftJoin('doctors as d', 'd.id', '=', 'pc.doctor_id')->select('pc.id', 'pr.patient_id', 'pr.patient_name', 'b.branch_name', 'pc.medical_record_id', 'd.doctor_name', DB::raw("DATE_FORMAT(pc.created_at, '%d/%b/%Y') AS cdate"))->where('pc.branch_id', $this->branch)->whereDate('pc.created_at', Carbon::today())->orderByDesc('pc.id')->get();
        return view('certificate.index', compact('records'));
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
        $ctypes = DB::table('certificate_types')->where('category', 'license')->get();
        $certificate = DB::table('patient_certificates as pc')->leftJoin('patient_registrations as pr', 'pc.patient_id', '=', 'pr.id')->leftJoin('branches as b', 'b.id', '=', 'pc.branch_id')->leftJoin('doctors as d', 'd.id', '=', 'pc.doctor_id')->select('pc.*', 'pr.patient_id', 'pr.patient_name', 'b.branch_name', 'd.doctor_name', DB::raw("DATE_FORMAT(pc.created_at, '%d/%b/%Y') AS cdate"))->where('pc.id', $id)->first();
        $details = DB::table('patient_certificate_details')->where('patient_certificate_id', $id)->get();
        return view('certificate.edit', compact('ctypes', 'certificate', 'details'));
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
        try{
            $user = Auth::user()->id;
            $time = Carbon::now();
            $pc = PC::find($id);
            PC::where('id', $id)->update(['updated_by' => $user, 'updated_at' => $time]);
            DB::table('patient_certificate_details')->where('patient_certificate_id', $id)->delete();
            if(!empty($input['certificate_type'])):
                for($i=0; $i<count($request->certificate_type); $i++):
                    $data[] = [
                        'patient_certificate_id' => $id,
                        'certificate_type' => $request->certificate_type[$i],
                        'fee' => $request->fee[$i],
                        'status' => $request->status[$i],
                        'notes' => $request->notes[$i],
                        'created_at' => $pc->created_at,
                        'updated_at' => $time,
                    ];
                endfor;
                DB::table('patient_certificate_details')->insert($data);
            endif;
        }catch(Exception $e){
            throw $e;
        }

        return redirect()->route('certificate.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PC::find($id)->delete();
        return redirect()->route('certificate.index')
                        ->with('success','Record deleted successfully');
    }
}
