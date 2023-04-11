<?php

namespace App\Http\Controllers;

use App\Models\PostOperativeInstruction;
use Illuminate\Http\Request;

class PostOperativeInstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:post-operative-instruction-list|post-operative-instruction-create|post-operative-instruction-edit|post-operative-instruction-delete', ['only' => ['index','store']]);
         $this->middleware('permission:post-operative-instruction-create', ['only' => ['create','store']]);
         $this->middleware('permission:post-operative-instruction-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:post-operative-instruction-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $records = PostOperativeInstruction::all();
        return view('poi.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('poi.create');
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
            'name' => 'required',
        ]);
        $input = $request->all();
        PostOperativeInstruction::create($input);
        return redirect()->route('poi.index')->with('success','Instruction created successfully');
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
        $poi = PostOperativeInstruction::find($id);
        return view('poi.edit', compact('poi'));
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
            'name' => 'required',
        ]);
        $input = $request->all();
        $poi = PostOperativeInstruction::find($id);
        $poi->update($input);
        return redirect()->route('poi.index')->with('success','Instruction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PostOperativeInstruction::find($id)->delete();
        return redirect()->route('poi.index')->with('success','Instruction deleted successfully');
    }
}
