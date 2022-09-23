<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeExpenseHead;
use DB;

class IncomeExpenseHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heads = DB::table('income_expense_heads')->selectRaw("id, name, CASE WHEN type='I' THEN 'Income' ELSE 'Expense' END AS type")->get();
        return view('ieheads.index', compact('heads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ieheads.create');
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
            'name' => 'required|unique:income_expense_heads,name',
            'type' => 'required',
        ]);
        $input = $request->all();
        $branch = IncomeExpenseHead::create($input);
        return redirect()->route('income-expense-heads.index')
                        ->with('success','Head created successfully');
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
        $head = IncomeExpenseHead::find($id);
        return view('ieheads.edit', compact('head'));
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
            'name' => 'required|unique:income_expense_heads,name,'.$id,
            'type' => 'required',
        ]);
        $input = $request->all();
        $head = IncomeExpenseHead::find($id);
        $head->update($input);
        return redirect()->route('income-expense-heads.index')
                        ->with('success','Head updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        IncomeExpenseHead::find($id)->delete();
        return redirect()->route('income-expense-heads.index')
                        ->with('success','Head deleted successfully');
    }
}
