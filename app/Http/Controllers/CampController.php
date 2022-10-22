<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camp;
use Carbon\Carbon;
use DB;

class CampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:camp-list|camp-create|camp-edit|camp-delete', ['only' => ['index','store']]);
        $this->middleware('permission:camp-create', ['only' => ['create','store']]);
        $this->middleware('permission:camp-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:camp-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    public function index()
    {
        $camps = Camp::leftJoin('camp_masters as m', 'camps.camp_id', '=', 'm.id')->where('m.branch', $this->branch)->whereDate('camps.created_at', Carbon::today())->select('camps.id', 'camps.patient_name', 'camps.age', 'camps.camp_date', 'm.camp_id', 'm.venue', 'm.address')->orderByDesc("camps.id")->get();
        return view('camp.index', compact('camps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $camp = DB::table('camp_masters')->find($id);
        return view('camp.create', compact('camp'));
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
            'patient_name' => 'required',
            'camp_date' => 'required',
            'age' => 'required',
        ]);
        $input = $request->all();
        $input['camp_date'] = Carbon::createFromFormat('d/M/Y', $request->camp_date);
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try{
            $camp = Camp::create($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('camp.index')->with('success','Record created successfully');
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
        $camp = Camp::find($id);
        $campm = DB::table('camp_masters')->find($camp->camp_id);
        return view('camp.edit', compact('camp', 'campm'));
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
            'patient_name' => 'required',
            'camp_date' => 'required',
            'age' => 'required',
        ]);
        $input = $request->all();
        $camp = Camp::find($id);
        $input['camp_date'] = Carbon::createFromFormat('d/M/Y', $request->camp_date);
        $input['created_by'] = $camp->getOriginal('created_by');
        $input['updated_by'] = $request->user()->id;
        try{
            $camp->update($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('camp.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Camp::find($id)->delete();
        return redirect()->route('camp.index')->with('success','Record deleted successfully');
    }
}
