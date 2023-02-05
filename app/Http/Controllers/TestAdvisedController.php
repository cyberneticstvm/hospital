<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\TestsAdvised;
use DB;

class TestAdvisedController extends Controller
{
    public function index(){
        $tests = TestsAdvised::orderByDesc('created_at')->get();
        return view('tests-advised.index', compact('tests'));
    }

    public function edit($id){
        $test = TestsAdvised::find($id);        
        return view('tests-advised.edit', compact('test'));
    }

    public function update(Request $request, $id){
        $test = TestsAdvised::find($id);
        $this->validate($request, [
            'status' => 'required',
        ]);
        $input = $request->all();
        if($request->att):
            $doc = $request->file('att');
            $fname = 'tests-advised/'.$test->medical_record_id.'/'.$doc->getClientOriginalName();
            Storage::disk('public')->putFileAs($fname, $doc, '');
            $input['attachment'] = $doc->getClientOriginalName();
        endif;
        $test->update($input);
        return redirect()->route('tests.advised')->with('success','Record updated successfully');
    }
}
