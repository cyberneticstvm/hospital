<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampMaster;
use Carbon\Carbon;
use DB;

class CampMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct(){
        $this->middleware('permission:campmaster-list|campmaster-create|campmaster-edit|campmaster-delete', ['only' => ['index','store']]);
        $this->middleware('permission:campmaster-create', ['only' => ['create','store']]);
        $this->middleware('permission:campmaster-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:campmaster-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }

    public function index()
    {
        $camps = DB::table('camp_masters as c')->leftJoin('branches as b', 'c.branch', '=', 'b.id')->selectRaw("c.id, c.camp_id, c.venue, c.address, DATE_FORMAT(c.from, '%d/%b/%Y') AS fdate, DATE_FORMAT(c.to, '%d/%b/%Y') AS tdate, b.branch_name, t.name as type_name")->leftJoin('camp_types as t', 'c.type', '=', 't.id')->where('c.branch', $this->branch)->orderByDesc("c.id")->get();
        return view('campmaster.index', compact('camps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ctypes = DB::table('camp_types')->orderBy('name')->get();
        $users = DB::table('users')->orderBy('name')->get();
        return view('campmaster.create', compact('ctypes', 'users'));
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
            'venue' => 'required',
            'address' => 'required',
            'cordinator' => 'required',
            'optometrist' => 'required',
            'type' => 'required',
        ]);
        $input = $request->all();
        $input['optometrist'] = implode(',', $request->optometrist);
        $input['camp_id'] = CampMaster::selectRaw("CONCAT_WS('-', 'CAMP', LPAD(IFNULL(max(id)+1, 1), 5, '0')) AS nextid")->value('nextid');
        $input['from'] = Carbon::createFromFormat('d/M/Y', $request->from);
        $input['to'] = Carbon::createFromFormat('d/M/Y', $request->to);
        $input['created_by'] = $request->user()->id;
        $input['updated_by'] = $request->user()->id;
        $input['branch'] = $this->branch;
        try{
            $camp = CampMaster::create($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('campmaster.index')->with('success','Record created successfully');
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
        $ctypes = DB::table('camp_types')->orderBy('name')->get();
        $users = DB::table('users')->orderBy('name')->get();
        $camp = CampMaster::find($id);
        return view('campmaster.edit', compact('ctypes', 'users', 'camp'));
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
            'venue' => 'required',
            'address' => 'required',
            'cordinator' => 'required',
            'optometrist' => 'required',
            'type' => 'required',
        ]);
        $input = $request->all();
        $camp = CampMaster::find($id);
        $input['optometrist'] = implode(',', $request->optometrist);
        $input['camp_id'] = $camp->getOriginal('camp_id');
        $input['from'] = Carbon::createFromFormat('d/M/Y', $request->from);
        $input['to'] = Carbon::createFromFormat('d/M/Y', $request->to);
        $input['created_by'] = $camp->getOriginal('created_by');
        $input['updated_by'] = $request->user()->id;
        $input['branch'] = $camp->getOriginal('branch');
        try{
            $camp->update($input);
        }catch(Exception $e){
            throw $e;
        }
        return redirect()->route('campmaster.index')->with('success','Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CampMaster::find($id)->delete();
        return redirect()->route('campmaster.index')->with('success','Record deleted successfully');
    }
}
