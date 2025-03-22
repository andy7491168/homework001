<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 

class LineItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('line_items')->truncate();
        DB::table('campaigns')->truncate();

        $jsonPath = database_path('data/line_items.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found: " . $jsonPath);
            return;
        }

        $jsonData = File::get($jsonPath);
        $lineItems = json_decode($jsonData, true);

        if ($lineItems === null) {
            $this->command->error("Invalid JSON format.");
            return;
        }

        $lineItemArray = [];
        $campaginTmpArray = [];
        foreach ($lineItems as $item) {
            $tmpItemArray = array();
            $campaginTmpArray[$item['campaign_id']] = $item['campaign_name'];
            $tmpItemArray['campaign_id'] = $item['campaign_id'];
            $tmpItemArray['name'] = $item['line_item_name'];
            $tmpItemArray['booked_amount'] = $item['booked_amount'];
            $tmpItemArray['actual_amount'] = $item['actual_amount'];
            $tmpItemArray['adjustments'] = $item['adjustments'];            
            array_push($lineItemArray,$tmpItemArray);
        }

        $campaginArray = [];
        foreach ($campaginTmpArray as $key=>$val) {
            $tmpCampaginArray = array();
            $tmpCampaginArray['id'] = $key;         
            $tmpCampaginArray['name'] = $val;         
            array_push($campaginArray,$tmpCampaginArray);
        }
    
        DB::table('line_items')->insert($lineItemArray);
        DB::table('campaigns')->insert($campaginArray);
        
    }
}