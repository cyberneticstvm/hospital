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
    protected $vision_extras;

    public function __construct(){
        $this->vision_extras = array('sel_1' => 1, 'sel_2' => 2, 'sel_3' => 3, 'sel_4' => 4, 'sel_5' => 5, 'sel_6' => 6, 'sel_7' => 7, 'sel_8' => 8, 'sel_9' => 9, 'sel_10' => 10, 'sel_11' => 11, 'sel_12' => 12, 'sel_13' => 13, 'sel_14' => 14, 'sel_15' => 15, 'sel_16' => 16, 'sel_17' => 17, 'sel_18' => 18, 'sel_19' => 19, 'sel_20' => 20);
    } 
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
            //$data = DB::table('products')->select('id', 'product_name as name')->get();
            $data = DB::table('products as p')->leftJoin('medicine_types as t', 'p.medicine_type', 't.id')->select('p.id', DB::raw("CONCAT_WS(' - ', p.product_name, t.name) AS name"))->get();
        }
        if($type == 'dosage'){
            $data = DB::table('dosages')->select('id', 'dosage as name')->get();
        }
        if($type == 'radiology'){
            $data = DB::table('lab_types')->where('category_id', 2)->select('id', 'lab_type_name as name')->get();
        }
        if($type == 'clinic'){
            $data = DB::table('lab_types')->where('category_id', 1)->select('id', 'lab_type_name as name')->get();
        }
        if(array_key_exists($type, $this->vision_extras)){
            $data = DB::table('vision_extras')->where('cat_id', $this->vision_extras[$type])->select('id', 'name')->get();
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
        if(array_key_exists($type, $this->vision_extras)):            
            $this->validate($request, [
                'name' => 'required',
            ]);            
            $input = $request->all();
            DB::table('vision_extras')->insert(
                ['name' => $request->name, 'cat_id' => $this->vision_extras[$type]]
            );
            return response()->json(['success'=>'Record is successfully updated']);
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
