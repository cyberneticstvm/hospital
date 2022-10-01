<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HelperController extends Controller
{
    public function getMedicineType($mid){
        $data = DB::table('medicine_types as m')->leftJoin('products as p', 'm.id', '=', 'p.medicine_type')->select('m.id', 'm.name', 'm.default_qty', 'm.default_dosage')->where('p.id', $mid)->first();
        return response()->json($data);
    }
}
