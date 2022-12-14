<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalFitnessHead;
use Carbon\Carbon;
use DB;

class MedicalFitnessHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:medical-fitness-head-list|medical-fitness-head-create|medical-fitness-head-edit|medical-fitness-head-delete', ['only' => ['index','store']]);
        $this->middleware('permission:medical-fitness-head-create', ['only' => ['create','store']]);
        $this->middleware('permission:medical-fitness-head-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:medical-fitness-head-delete', ['only' => ['destroy']]);

        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $heads = MedicalFitnessHead::all();
        return view('medical-fitness-head.index', compact('heads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medical-fitness-head.create');
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
            'name' => 'required|unique:medical_fitness_heads,name',
        ]);
        $input = $request->all();
        try{
            $mfit = MedicalFitnessHead::create($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('mfithead.index')->with('success','Record created successfully');
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
        $head = MedicalFitnessHead::find($id);
        return view('medical-fitness-head.edit', compact('head'));
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
            'name' => 'required|unique:medical_fitness_heads,name,'.$id,
        ]);
        $input = $request->all();
        try{
            $mfit = MedicalFitnessHead::find($id);
            $mfit->update($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('mfithead.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MedicalFitnessHead::find($id)->delete();
        return redirect()->route('mfithead.index')
                        ->with('success','Head deleted successfully');
    }
}
