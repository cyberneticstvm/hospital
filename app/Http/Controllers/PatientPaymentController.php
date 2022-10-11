<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PatientPayment as PP;
use Carbon\Carbon;
use DB;

class PatientPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
         $this->middleware('permission:patient-payments-list|patient-payments-create|patient-payments-edit|patient-payments-delete', ['only' => ['index', 'store']]);
         $this->middleware('permission:patient-payments-list', ['only' => ['index']]);
         $this->middleware('permission:patient-payments-create', ['only' => ['store']]);
         $this->middleware('permission:patient-payments-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:patient-payments-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $incomes = PP::leftJoin('patient_medical_records as pmr', 'patient_payments.medical_record_id', '=', 'pmr.id')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->leftJoin('payment_modes as pm', 'pm.id', '=', 'patient_payments.payment_mode')->select("patient_payments.id", "patient_payments.amount", "patient_payments.medical_record_id", "patient_payments.notes", "pm.name", "pr.patient_name", "pr.patient_id")->whereDate('patient_payments.created_at', Carbon::today())->orderByDesc("patient_payments.id")->get();
        return view('patient-payment.index', compact('incomes'));
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
            'amount' => 'required',
            'payment_mode' => 'required',
            'medical_record_id' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $input['branch'] = $request->session()->get('branch');
        $pp = PP::create($input);
        return redirect()->route('patient-payment.index')->with('success','Payment recorded successfully');
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
        $heads = DB::table('income_expense_heads')->where('type', 'I')->where('category', 'patient')->orderBy('name')->get();
        $pmodes = DB::table('payment_modes')->orderBy('name')->get();
        $medical_record_id = $request->medical_record_id;
        $patient = DB::table('patient_registrations as pr')->leftJoin('patient_medical_records as pmr', 'pmr.patient_id', '=', 'pr.id')->where('pmr.id', $request->medical_record_id)->select('pr.id', 'pr.patient_name', 'pr.patient_id', DB::raw("DATE_FORMAT(pmr.created_at, '%d/%b/%Y') AS cdate"))->first();

        $reg_fee = DB::table('patient_medical_records as pmr')->leftJoin('patient_registrations as pr', 'pmr.patient_id', '=', 'pr.id')->where('pmr.id', $request->medical_record_id)->value('pr.registration_fee');

        $consultation_fee = DB::table('patient_medical_records as pmr')->leftJoin('patient_references as pr', 'pmr.mrn', '=', 'pr.id')->where('pmr.id', $request->medical_record_id)->value('pr.doctor_fee');

        $procedure_fee = DB::table('patient_procedures as pp')->leftJoin('patient_medical_records as pmr', 'pp.medical_record_id', '=', 'pmr.id')->where('pmr.id', $request->medical_record_id)->sum('fee');

        $certificate_fee = DB::table('patient_certificates as pc')->leftJoin('patient_certificate_details as pcd', 'pc.id', '=', 'pcd.patient_certificate_id')->where('pc.medical_record_id', $request->medical_record_id)->where('pcd.status', 'I')->sum('pcd.fee');

        $pharmacy = DB::table('patient_medicine_records')->where('medical_record_id', $request->medical_record_id)->sum('total');
        
        $clinical_lab = 0.00;
        $radiology_lab = 0.00;

        $payments = PP::where('medical_record_id', $request->medical_record_id)->leftJoin('payment_modes as p', 'patient_payments.payment_mode', '=', 'p.id')->select('patient_payments.id', 'patient_payments.amount', 'patient_payments.notes', 'p.name')->get();

        $fee = array($certificate_fee, $clinical_lab, $consultation_fee, $pharmacy, $procedure_fee, $reg_fee);
        $tot = $reg_fee+$consultation_fee+$procedure_fee+$certificate_fee+$pharmacy+$radiology_lab+$clinical_lab;
        return view('patient-payment.fetch', compact('patient', 'medical_record_id', 'heads', 'pmodes', 'fee', 'tot', 'payments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = PP::find($id);
        $pmodes = DB::table('payment_modes')->orderBy('name')->get();
        $patient = DB::table('patient_registrations')->find($payment->patient_id);
        return view('patient-payment.edit', compact('payment', 'pmodes', 'patient'));
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
            'amount' => 'required',
            'payment_mode' => 'required',
            'medical_record_id' => 'required',
        ]);
        $input = $request->all();
        $created_at = (!empty($request->created_at)) ? Carbon::createFromFormat('d/M/Y', $input['created_at'])->format('Y-m-d H:i:s') : Carbon::now();
        $branch = $request->session()->get('branch');
        $pp = PP::where('id', $id)->update(['branch' => $branch, 'amount' => $request->amount, 'payment_mode' => $request->payment_mode, 'notes' => $request->notes, 'created_at' => $created_at]);
        return redirect()->route('patient-payment.index')->with('success','Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PP::find($id)->delete();
        return redirect()->route('patient-payment.list')
                        ->with('success','Payment deleted successfully');
    }
}
