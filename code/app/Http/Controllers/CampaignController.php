<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\DTOs\CampaignFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class CampaignController extends Controller
{
   
    protected $campaignService;
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }   
    public function add(Request $request)
    {
        $result = $this->campaignService->add($request);
        return response()->json(['msg'=>$result['msg']],  $result['status']);
    }

    public function export()
    {
        try {
            $campaigns = $this->campaignService->getCampaigns(new CampaignFilter(
                '', 
                'booked_amount', 
                '>=', 
                null, 
                'campaigns.name', 
                'asc'
            ));

            $csvFileName = 'uploads/campaigns_export_' . now()->format('Ymd_His') . '.csv';

            $handle = fopen(public_path($csvFileName), 'w');
            
            fputcsv($handle, [
                'Campaign ID', 
                'Campaign Name', 
                'Booked Amount', 
                'Actual Amount', 
                'Adjustments', 
                'Invoice Amount'
            ]);
            
            // Add data rows
            foreach ($campaigns as $campaign) {
                fputcsv($handle, [
                    $campaign->id,
                    $campaign->name,
                    $campaign->booked_amount,
                    $campaign->actual_amount,
                    $campaign->adjustments,
                    $campaign->invoice_amount
                ]);
            }
            fclose($handle);
            $filePath = public_path($csvFileName);

            $s3Path =$csvFileName;
            Storage::disk('s3')->put($s3Path, file_get_contents($filePath));
            $fileUrl = Storage::disk('s3')->url($s3Path);
            return redirect()->away($fileUrl);
    
        } catch (\Exception $e) {
            Log::error('Error exporting campaigns to CSV: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
