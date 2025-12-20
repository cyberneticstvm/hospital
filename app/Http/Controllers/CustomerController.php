<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:customer-list|customer-create|customer-edit|customer-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::withTrashed()->latest()->get();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $this->validate($request, [
            'name' => 'required|unique:customers,name',
            'contact_number' => 'required|numeric',
            'address' => 'required',
            'gstin' => 'nullable',
            'opening_balance' => 'required|numeric|gte:0',
        ]);
        $inputs['created_by'] = $request->user()->id;
        $inputs['updated_by'] = $request->user()->id;
        Customer::create($inputs);
        return redirect()->route('customer.list')
            ->with('success', 'Customer created successfully');
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
        $customer = Customer::findOrFail(decrypt($id));
        return view('customer.edit', compact('customer'));
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
            'name' => 'required|unique:customers,name,' . decrypt($id),
            'contact_number' => 'required|numeric',
            'address' => 'required',
            'gstin' => 'nullable',
            'opening_balance' => 'required|numeric|gte:0',
        ]);
        $inputs['updated_by'] = $request->user()->id;
        Customer::findOrFail(decrypt($id))->update($inputs);
        return redirect()->route('customer.list')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::findOrFail(decrypt($id))->delete();
        return redirect()->route('customer.list')
            ->with('success', 'Customer deleted successfully');
    }
}
