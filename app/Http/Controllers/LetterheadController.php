<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterHead;
use Carbon\Carbon;
use DB;

class LetterheadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:letterhead-list|letterhead-create|letterhead-edit|letterhead-delete', ['only' => ['index','store']]);
        $this->middleware('permission:letterhead-create', ['only' => ['create','store']]);
        $this->middleware('permission:letterhead-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:letterhead-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $matters = LetterHead::leftJoin('branches as b', 'letter_heads.branch', '=', 'b.id')->select(DB::raw("DATE_FORMAT(letter_heads.date, '%d/%b/%Y') AS rdate"), 'b.branch_name', 'letter_heads.from', 'letter_heads.id', 'letter_heads.to', 'letter_heads.subject', 'letter_heads.matter', 'letter_heads.description')->where('letter_heads.branch', $this->branch)->orderByDesc('letter_heads.date')->get();
        return view('letterheads.index', compact('matters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('letterheads.create');
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
            'from' => 'required',
            'to' => 'required',
            'date' => 'required',
            'subject' => 'required',
            'matter' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        $input['branch'] = $this->branch;
        $input['date'] = (!empty($request->date)) ? Carbon::createFromFormat('d/M/Y', $request['date'])->format('Y-m-d') : NULL;
        try{
            $letterhead = LetterHead::create($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('letterheads.index')->with('success','Record created successfully');
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
        $matter = LetterHead::find($id);
        return view('letterheads.edit', compact('matter'));
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
            'from' => 'required',
            'to' => 'required',
            'date' => 'required',
            'subject' => 'required',
            'matter' => 'required',
        ]);
        $input = $request->all();
        $input['updated_by'] = $request->user()->id;
        $input['date'] = (!empty($request->date)) ? Carbon::createFromFormat('d/M/Y', $request['date'])->format('Y-m-d') : NULL;
        try{
            $letterhead = LetterHead::find($id);
            $letterhead->update($input);
        }catch(Exception $e){
            throw $e;
        }        
        return redirect()->route('letterheads.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LetterHead::find($id)->delete();
        return redirect()->route('letterheads.index')
                        ->with('success','Record deleted successfully');
    }
}
