<?php

namespace App\Helper;
use DB;

class Helper{
    public static function getAvailableStock($product, $batch, $from_branch){
        if($from_branch == 0):
            $total_purchase = DB::table('purchases')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->sum('qty');
        else:
            $total_purchase = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('to_branch', '=', $from_branch)->sum('qty');
            $total_transfer = DB::table('product_transfers')->where('product', '=', $product)->where('batch_number', '=', $batch)->where('from_branch', '=', $from_branch)->sum('qty');
        endif;
        return $total_purchase - $total_transfer;
    }
}

?>