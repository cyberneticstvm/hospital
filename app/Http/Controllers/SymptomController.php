<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\Diagnosis;
use DB;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $type = $type; $data = '';
        if($type == 'symptomSelect'){
            $data = DB::table('symptoms')->select('id', 'symptom_name as name')->get();
        }
        if($type == 'diagnosisSelect'){
            $data = DB::table('diagnosis')->select('id', 'diagnosis_name as name')->get();
        }
        if($type == 'medicine'){
            $data = DB::table('medicine')->select('id', 'medicine_name as name')->get();
        }
        if($type == 'dosage'){
            $data = DB::table('dosages')->select('id', 'dosage as name')->get();
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type)
    {
        $type = $type;
        if($type == 'symptomSelect'):
            $this->validate($request, [
                'symptom_name' => 'required|unique:symptoms,symptom_name',
            ]);
            $input = $request->all();
            $symptom = Symptom::create($input);            
            return response()->json(['success'=>'Symptom is successfully added']);
        endif;
        if($type == 'diagnosisSelect'):            
            $this->validate($request, [
                'diagnosis_name' => 'required|unique:diagnosis,diagnosis_name',
            ]);            
            $input = $request->all();
            DB::table('diagnosis')->insert(
                ['diagnosis_name' => $request->diagnosis_name]
            );
            return response()->json(['success'=>'Diagnosis is successfully added']);
        endif;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
