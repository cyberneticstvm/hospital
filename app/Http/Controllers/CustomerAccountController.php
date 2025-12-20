<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAccount;
use Illuminate\Http\Request;

class CustomerAccountController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:customerpayment--list|customer-payment-create|customer-payment-edit|customer-payment-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:customer-payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-payment-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = CustomerAccount::withTrashed()->where('type', 'cr')->latest()->get();
        return view('customer.account.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(string $id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        return view('customer.account.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $id)
    {
        $inputs = $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'pdate' => 'required|date',
            'notes' => 'nullable'
        ]);
        $inputs['customer_id'] = decrypt($id);
        $inputs['type'] = 'cr';
        $inputs['created_by'] = $request->user()->id;
        $inputs['updated_by'] = $request->user()->id;
        CustomerAccount::create($inputs);
        return redirect()->route('customer.account.list')
            ->with('success', 'Customer payment recorded successfully');
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
        $payment = CustomerAccount::findOrFail(decrypt($id));
        $customer = Customer::findOrFail($payment->customer_id);
        return view('customer.account.edit', compact('payment', 'customer'));
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
        $inputs = $this->validate($request, [
            'amount' => 'required|numeric|gt:0',
            'pdate' => 'required|date',
            'notes' => 'nullable'
        ]);
        $inputs['updated_by'] = $request->user()->id;
        CustomerAccount::findOrFail(decrypt($id))->update($inputs);
        return redirect()->route('customer.account.list')
            ->with('success', 'Customer payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerAccount::findOrFail(decrypt($id))->delete();
        return redirect()->route('customer.account.list')
            ->with('success', 'Customer payment deleted successfully');
    }
}
