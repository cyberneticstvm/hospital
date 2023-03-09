<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procedure;
use App\Models\InhouseCamp;
use App\Models\InhouseCampProcedure;
use Carbon\Carbon;
use DB;

class InhouseCampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('permission:inhousecamp-list|inhousecamp-create|inhousecamp-edit|inhousecamp-delete', ['only' => ['index','store']]);
        $this->middleware('permission:inhousecamp-create', ['only' => ['create','store']]);
        $this->middleware('permission:inhousecamp-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:inhousecamp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $camps = InhouseCamp::all();
        $procedures = Procedure::all();
        return view('inhouse-camps.index', compact('camps', 'procedures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $procedures = Procedure::all();
        return view('inhouse-camps.create', compact('procedures'));
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
            'from_date' => 'required',
            'to_date' => 'required',
            'name' => 'required|unique:inhouse_camps,name',
            'validity' => 'required',
            'status' => 'required',
            'procedure' => 'present|array',
        ]);
        $input = $request->all();
        $input['from_date'] = Carbon::createFromFormat('d/M/Y', $request->from_date);
        $input['to_date'] = Carbon::createFromFormat('d/M/Y', $request->to_date);
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        try{
            $camp = InhouseCamp::create($input);
            $data = [];
            foreach($request->procedure as $key => $proc):
                $data [] = [
                    'camp_id' => $camp->id,
                    'procedure' => $proc,
                ];
            endforeach;
            InhouseCampProcedure::insert($data);
        }catch(Exception $e){
            //throw $e;
            return redirect()->back()->with('error', $e)->withInput();
        }
        return redirect()->route('inhousecamp.index')->with('success','Record created successfully');
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
        $camp = InhouseCamp::find($id);
        $procedures = Procedure::all();
        return view('inhouse-camps.edit', compact('camp', 'procedures'));
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
        $camp = InhouseCamp::find($id);
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required',
            'name' => 'required|unique:inhouse_camps,name,'.$id,
            'validity' => 'required',
            'status' => 'required',
            'procedure' => 'present|array',
        ]);
        $input = $request->all();
        $input['from_date'] = Carbon::createFromFormat('d/M/Y', $request->from_date);
        $input['to_date'] = Carbon::createFromFormat('d/M/Y', $request->to_date);
        $input['created_by'] = $camp->getOriginal('created_by');
        $input['updated_by'] = $request->user()->id;
        try{
            $camp->update($input);            
            $data = [];
            foreach($request->procedure as $key => $proc):
                $data [] = [
                    'camp_id' => $camp->id,
                    'procedure' => $proc,
                ];
            endforeach;
            InhouseCampProcedure::where('camp_id', $id)->delete();
            InhouseCampProcedure::insert($data);
        }catch(Exception $e){
            //throw $e;
            return redirect()->back()->with('error', $e)->withInput();
        }
        return redirect()->route('inhousecamp.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        InhouseCamp::where('id', $id)->delete();
        return redirect()->route('inhousecamp.index')
                        ->with('success','Record deleted successfully');
    }
}
