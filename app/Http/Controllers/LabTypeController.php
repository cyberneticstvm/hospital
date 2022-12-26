<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\LabType;
use Carbon\Carbon;
use DB;

class LabTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:lab-type-list|lab-type-create|lab-type-edit|lab-type-delete', ['only' => ['index','store']]);
        $this->middleware('permission:lab-type-create', ['only' => ['create','store']]);
        $this->middleware('permission:lab-type-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:lab-type-delete', ['only' => ['destroy']]);
   }

    public function index()
    {
        $ltypes = DB::table('lab_types as l')->leftJoin('lab_categories as c', 'l.category_id', '=', 'c.id')->select('l.id', 'l.lab_type_name', 'l.description', 'l.fee', 'c.category_name as category')->get();
        return view('lab.lab-type-register', compact('ltypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DB::table('lab_categories')->get();
        $surgery_types = DB::table('surgery_types')->get();
        return view('lab.lab-type-create', compact('categories', 'surgery_types'));
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
            'category_id' => 'required',
            'lab_type_name' => 'required',
            'fee' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $ltype = LabType::create($input);
        return redirect()->route('ltype.index')->with('success','Lab Test Type created successfully');
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
        $categories = DB::table('lab_categories')->get();
        $ltype = LabType::find($id);
        $surgery_types = DB::table('surgery_types')->get();
        return view('lab.lab-type-edit', compact('categories', 'ltype', 'surgery_types'));
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
            'category_id' => 'required',
            'lab_type_name' => 'required',
            'fee' => 'required',
        ]);
        $input = $request->all();
        $ltype = LabType::find($id);
        $input['created_by'] = $ltype->getOriginal('created_by');
        $ltype->update($input);
        return redirect()->route('ltype.index')->with('success','Lab Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LabType::find($id)->delete();
        return redirect()->route('ltype.index')
                        ->with('success','Lab Type deleted successfully');
    }
}
