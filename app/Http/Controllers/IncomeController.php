<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\PatientPayment as PP;
use Carbon\Carbon;
use DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
         $this->middleware('permission:income-list|income-create|income-edit|income-delete', ['only' => ['index','store']]);
         $this->middleware('permission:income-create', ['only' => ['create','store']]);
         $this->middleware('permission:income-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:income-delete', ['only' => ['destroy']]);
         $this->branch = session()->get('branch');
    }
    public function index()
    {
        $incomes = Income::leftJoin('branches as b', 'incomes.branch', '=', 'b.id')->leftJoin('income_expense_heads as ie', 'incomes.head', '=', 'ie.id')->select('incomes.id', 'incomes.description', 'incomes.amount', 'b.branch_name', 'ie.name as head', DB::raw("DATE_FORMAT(incomes.date, '%d/%b/%Y') AS edate"))->where('incomes.branch', $this->branch)->whereDate('incomes.created_at', Carbon::today())->orderByDesc("incomes.id")->get();
        return view('income.index', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = DB::table('branches')->get();    
        $heads = DB::table('income_expense_heads')->where('type', 'I')->get();    
        return view('income.create', compact('branches', 'heads'));
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
            'date' => 'required',
            'amount' => 'required',
            'head' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
        $input['branch'] = $this->branch;
        $input['date'] = (!empty($request->date)) ? Carbon::createFromFormat('d/M/Y', $request['date'])->format('Y-m-d') : NULL;
        $income = Income::create($input);        
        return redirect()->route('income.index')->with('success','Income recorded successfully');
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
        $branches = DB::table('branches')->get();
        $income = Income::find($id);
        $heads = DB::table('income_expense_heads')->where('type', 'I')->get();    
        return view('income.edit', compact('branches', 'income', 'heads'));
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
            'date' => 'required',
            'amount' => 'required',
            'head' => 'required',
        ]);
        $input = $request->all();
        $income = Income::find($id);
        $input['created_by'] = $income->getOriginal('created_by');
        $input['branch'] = $this->branch;
        $input['date'] = (!empty($request->date)) ? Carbon::createFromFormat('d/M/Y', $request['date'])->format('Y-m-d') : NULL;        
        $income->update($input);        
        return redirect()->route('income.index')->with('success','Income updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Income::find($id)->delete();
        return redirect()->route('income.index')
                        ->with('success','Record deleted successfully');
    }
}
