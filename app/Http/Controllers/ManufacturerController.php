<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    function __construct(){
         $this->middleware('permission:manufacturer-list|manufacturer-create|manufacturer-edit|manufacturer-delete', ['only' => ['index','store']]);
         $this->middleware('permission:manufacturer-create', ['only' => ['create','store']]);
         $this->middleware('permission:manufacturer-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:manufacturer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mans = Manufacturer::get();
        return view('manufacturer.index', compact('mans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manufacturer.create');
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
            'name' => 'required|unique:manufacturers,name',
        ]);
        $input = $request->all();
        $man = Manufacturer::create($input);
        return redirect()->route('manufacturer.index')->with('success','Manufacturer created successfully');
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
        $man = Manufacturer::find($id);
        return view('manufacturer.edit', compact('man'));
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
            'name' => 'required|unique:manufacturers,name,'.$id,
        ]);
        $input = $request->all();
        $man = Manufacturer::find($id);
        $man->update($input);
        return redirect()->route('manufacturer.index')->with('success','Manufacturer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Manufacturer::find($id)->delete();
        return redirect()->route('manufacturer.index')
                        ->with('success','Manufacturer deleted successfully');
    }
}
