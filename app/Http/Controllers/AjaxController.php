<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AjaxController extends Controller
{
    function getBatch(Request $request)
    {
        $inventory = Helper::getStock($request->product, Session::get('branch'), $request->qty);
        $op = "<option value=''>Select</option>";
        if ($inventory) :
            foreach ($inventory as $key => $inv) :
                $op .= "<option value='" . $inv->batch_number . "' data-qty='" . $inv->balance_qty . "'>" . $inv->batch_number . "  (" . $inv->balance_qty . " Qty in Hand)</option>";
            endforeach;
        endif;
        echo $op;
    }
}
