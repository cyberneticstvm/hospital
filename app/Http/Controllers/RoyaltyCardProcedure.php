<?php

namespace App\Http\Controllers;

use App\Models\RoyaltyCard;
use App\Models\Procedure;
use App\Models\RoyaltyCardProcedure as rcp;
use Exception;
use Illuminate\Http\Request;

class RoyaltyCardProcedure extends Controller
{
    function __construct()
    {
        //
    }

    public function index()
    {
        $rcards = RoyaltyCard::all();
        return view('extras.royalty-cards', compact('rcards'));
    }

    public function show(string $id)
    {
        $rcard = RoyaltyCard::findOrFail(decrypt($id));
        $procedures = Procedure::all();
        $rcp = rcp::where('royalty_card_id', $rcard->id)->get();
        return view('extras.royalty-card-procs', compact('rcard', 'procedures', 'rcp'));
    }

    public function store(Request $request, string $id)
    {
        try {
            rcp::where('royalty_card_id', $id)->delete();
            $data = [];
            foreach ($request->proc as $key => $proc):
                $data[] = [
                    'royalty_card_id' => $id,
                    'proc_id' => $proc,
                    'discount_percentage' => $request->disc[$key],
                ];
            endforeach;
            rcp::insert($data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('rcard.proc.index')->with('success', 'Procedure updated successfully');
    }
}
