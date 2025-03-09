<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\DTOs\CampaignFilter;
use Illuminate\Support\Facades\Log;
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

            $csvFileName = 'campaigns_export_' . now()->format('Ymd_His') . '.csv';

            $handle = fopen('php://memory', 'r+');
            
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
            rewind($handle);
            return response()->stream(
                function () use ($handle) {
                    fpassthru($handle);
                    fclose($handle); 
                },
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
                ]
            );
    
        } catch (\Exception $e) {
            Log::error('Error exporting campaigns to CSV: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
