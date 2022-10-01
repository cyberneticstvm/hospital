<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Carbon\Carbon;
use DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:income-list|income-create|income-edit|income-delete', ['only' => ['index','store']]);
         $this->middleware('permission:income-create', ['only' => ['create','store']]);
         $this->middleware('permission:income-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:income-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $incomes = Income::leftJoin('branches as b', 'b.id', '=', 'incomes.branch')->leftJoin('income_expense_heads as h', 'incomes.head', '=', 'h.id')->select('incomes.id', 'incomes.description', 'incomes.amount', DB::raw("DATE_FORMAT(incomes.date, '%d/%b/%Y') AS edate"), 'b.branch_name', 'h.name as head')->whereDate('incomes.created_at', Carbon::today())->orderByDesc('incomes.id')->get();
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
            'branch' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = $request->user()->id;
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
    public function fetch(){
        $medical_record_number = ""; $heads = []; $pmodes = [];
        $reg_fee = 0.00; $consultation_fee = 0.00; $procedure_fee = 0.00; $certificate_fee = 0.00; $medicine = 0.00; $tot = 0.00;
        return view('income.fetch', compact('medical_record_number', 'heads', 'pmodes', 'reg_fee', 'consultation_fee', 'procedure_fee', 'certificate_fee', 'medicine', 'tot'));
    }
    public function show(Request $request)
    {
        $this->validate($request, [
            'medical_record_number' => 'required',
        ]);
        $heads = DB::table('income_expense_heads')->where('type', 'I')->where('category', 'patient')->orderBy('name')->get();
        $pmodes = DB::table('payment_modes')->orderBy('name')->get();
        $medical_record_number = $request->medical_record_number;
        $reg_fee = 0.00;
        $consultation_fee = 0.00;
        $procedure_fee = 0.00;
        $certificate_fee = 0.00;
        $medicine = 0.00;
        $tot = $reg_fee+$consultation_fee+$procedure_fee+$certificate_fee+$medicine;
        return view('income.fetch', compact('medical_record_number', 'heads', 'pmodes', 'reg_fee', 'consultation_fee', 'procedure_fee', 'certificate_fee', 'medicine', 'tot'));
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
            'branch' => 'required',
        ]);
        $input = $request->all();
        $income = Income::find($id);
        $input['created_by'] = $income->getOriginal('created_by');
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
                        ->with('success','Income deleted successfully');
    }
}
