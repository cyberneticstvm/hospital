<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Spectacle;
use Carbon\Carbon;
use DB;

class SearchController extends Controller
{
    private $branch;

    function __construct(){

         $this->middleware('permission:spectacle-search', ['only' => ['spectaclefetch']]);

         $this->branch = session()->get('branch');         
    }

    public function spectaclesearch(){
        $spectacles = []; $search_term = '';
        return view('search.spectacle', compact('spectacles', 'search_term'));
    }

    public function spectaclefetch(Request $request){
        $this->validate($request, [
            'search_term' => 'required',
        ]);
        $input = $request->all();
        $search_term = $request->search_term;
        $spectacles = Spectacle::leftJoin('patient_medical_records AS m', 'spectacles.medical_record_id', '=', 'm.id')->leftJoin('patient_registrations AS p', 'm.patient_id', '=', 'p.id')->leftJoin('users AS u', 'spectacles.created_by', '=', 'u.id')->selectRaw("spectacles.id, spectacles.medical_record_id, p.id as pid, p.patient_name, p.patient_id, u.name AS optometrist, DATE_FORMAT(spectacles.created_at, '%d/%b/%Y') AS pdate")->where('m.id', $search_term)->orWhere('p.patient_name', 'LIKE', "%{$search_term}%")->orWhere('p.mobile_number', 'LIKE', "%{$search_term}%")->orWhere('p.patient_id', 'LIKE', "%{$search_term}%")->get();
        return view('search.spectacle', compact('spectacles', 'search_term'));
    }
}
