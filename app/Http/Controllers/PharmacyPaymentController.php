<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PatientPayment as PP;
use Carbon\Carbon;
use DB;

class PharmacyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:pharmacyd-payments-list|pharmacyd-payments-create|pharmacyd-payments-edit|pharmacyd-payments-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:pharmacyd-payments-list', ['only' => ['index']]);
        $this->middleware('permission:pharmacyd-payments-create', ['only' => ['store']]);
        $this->middleware('permission:pharmacyd-payments-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pharmacyd-payments-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    public function index()
    {
        $incomes = DB::table('patient_payments as pp')->leftJoin('pharmacies as p', 'p.id', '=', 'pp.pharmacy_id')->leftJoin('payment_modes as pm', 'pm.id', '=', 'pp.payment_mode')->select('pp.id', 'pp.pharmacy_id as billno', 'pp.amount', 'pp.notes', 'p.patient_name', 'p.other_info', 'pm.name')->where('pp.branch', $this->branch)->where('pp.pharmacy_id', '>', 0)->whereDate('pp.created_at', Carbon::today())->orderByDesc("pp.id")->get();
        return view('patient-payment.pharmacy-direct.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'bill_number' => 'required',
        ]);
        $patient = DB::table('pharmacies')->find($request->bill_number);
        $amount = DB::table('pharmacy_records')->where('pharmacy_id', $request->bill_number)->get()->sum('total');
        $pmodes = DB::table('payment_modes')->whereIn('id', [1, 2, 3, 4, 5, 6, 7])->orderBy('name')->get();
        $types = DB::table('payment_modes')->whereIn('id', [8, 9])->orderBy('name')->get();
        $payments = PP::where('pharmacy_id', $request->bill_number)->leftJoin('payment_modes as p', 'patient_payments.payment_mode', '=', 'p.id')->select('patient_payments.id', 'patient_payments.amount', 'patient_payments.notes', 'p.name')->get();
        if ($patient):
            return view('patient-payment.pharmacy-direct.fetch', compact('patient', 'amount', 'pmodes', 'payments', 'types'));
        else:
            return redirect()->back()->with('error', 'No records found.');
        endif;
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
        ]);
        $input = $request->all();
        $input['medical_record_id'] = 0;
        $input['patient_id'] = 0;
        $input['created_by'] = Auth::user()->id;
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $pp = PP::create($input);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->route('paypharma.index')->with('success', 'Payment recorded successfully');
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
        $payment = PP::find($id);
        $pmodes = DB::table('payment_modes')->whereIn('id', [1, 2, 3, 4, 5, 6, 7])->orderBy('name')->get();
        $types = DB::table('payment_modes')->whereIn('id', [8, 9])->orderBy('name')->get();
        $patient = DB::table('pharmacies')->find($payment->pharmacy_id);
        return view('patient-payment.pharmacy-direct.edit', compact('payment', 'pmodes', 'patient', 'types'));
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
        ]);
        $input = $request->all();
        $created_at = (!empty($request->created_at)) ? Carbon::createFromFormat('d/M/Y', $input['created_at'])->format('Y-m-d H:i:s') : Carbon::now();
        $pp = PP::where('id', $id)->update(['amount' => $request->amount, 'payment_mode' => $request->payment_mode, 'notes' => $request->notes, 'created_at' => $created_at]);
        return redirect()->route('paypharma.index')->with('success', 'Payment updated successfully');
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
        return redirect()->route('paypharma.list')
            ->with('success', 'Payment deleted successfully');
    }
}
