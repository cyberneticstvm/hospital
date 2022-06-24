<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\SurgeryType;
use Carbon\Carbon;
use DB;

class SurgeryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:surgery-type-list|surgery-type-create|surgery-type-edit|surgery-type-delete', ['only' => ['index','store']]);
        $this->middleware('permission:surgery-type-create', ['only' => ['create','store']]);
        $this->middleware('permission:surgery-type-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:surgery-type-delete', ['only' => ['destroy']]);
   }
    public function index()
    {
        $stypes = SurgeryType::get();
        return view('surgery.surgery-type-register', compact('stypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('surgery.surgery-type-create');
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
            'surgery_name' => 'required|unique:surgery_types,surgery_name',
            'fee' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $stype = SurgeryType::create($input);
        return redirect()->route('stype.index')->with('success','Surgery Type created successfully');
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
        $stype = SurgeryType::find($id);
        return view('surgery.surgery-type-edit', compact('stype'));
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
            'surgery_name' => 'required|unique:surgery_types,surgery_name,'.$id,
            'fee' => 'required',
        ]);
        $input = $request->all();
        $stype = SurgeryType::find($id);
        $input['created_by'] = $stype->getOriginal('created_by');
        $stype->update($input);
        return redirect()->route('stype.index')->with('success','Surgery Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SurgeryType::find($id)->delete();
        return redirect()->route('stype.index')
                        ->with('success','Surgery Type deleted successfully');
    }
}
