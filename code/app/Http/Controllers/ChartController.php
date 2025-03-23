<?php

namespace App\Http\Controllers;

use App\Models\LineItem;
class ChartController extends Controller
{
 
    public function show($id)   
    {

        $lineItems = LineItem::where('campaign_id', $id)
        ->orderBy('id', 'asc')
        ->get();
       
       $output = [];
       foreach($lineItems as $row){
            $tmp = [];
            $tmp = ["id"=>$row['id'],
            "name"=>$row['name'],
            "booked_amount"=>$row['booked_amount'],
            "actual_amount"=>$row['actual_amount']];
            $output[$row['id']] = $tmp; 
       }

       return view('chart', ['data' => $output]);
    }
}
