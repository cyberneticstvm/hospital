<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AjaxController extends Controller
{
    function getBatch(Request $request)
    {
        $inventory = Helper::getStock($request->product, $request->branch, $request->qty);
        $op = "<option value=''>Select</option>";
        if ($inventory) :
            foreach ($inventory as $key => $inv) :
                if ($inv->balanceQty > 0)
                    $op .= "<option value='" . $inv->batch_number . "' data-qty='" . $inv->balanceQty . "'>" . $inv->batch_number . "  (" . $inv->balanceQty . " Qty in Hand)</option>";
            endforeach;
        endif;
        echo $op;
    }
}
