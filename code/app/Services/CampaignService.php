<?php

namespace App\Services;

use App\Models\Campaign;
use App\DTOs\CampaignFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
class CampaignService
{
    public function getCampaigns(CampaignFilter $campaignFilter)
    {
        try {
            $query = Campaign::leftJoin('line_items', 'campaigns.id', '=', 'line_items.campaign_id')
                ->selectRaw('
                    campaigns.id, 
                    campaigns.name, 
                    campaigns.comments, 
                    SUM(line_items.booked_amount) as booked_amount, 
                    SUM(line_items.actual_amount) as actual_amount, 
                    SUM(line_items.adjustments) as adjustments, 
                    SUM(line_items.actual_amount) + SUM(line_items.adjustments) as invoice_amount
                ')
                ->where('campaigns.name', 'like', '%' . $campaignFilter->search . '%');

            if ($campaignFilter->filterValue !== null && $campaignFilter->filterValue !== '') {
                $allowedFields = ['booked_amount', 'actual_amount', 'adjustments'];
                $allowedOperators = ['>=', '<='];

                if ($campaignFilter->filterField === 'invoice_amount' && in_array($campaignFilter->filterOperator, $allowedOperators)) {
                    $query->havingRaw("SUM(line_items.actual_amount) + SUM(line_items.adjustments) " . $campaignFilter->filterOperator . " ?", [$campaignFilter->filterValue]);
                } else if (in_array($campaignFilter->filterField, $allowedFields) && in_array($campaignFilter->filterOperator, $allowedOperators)) {
                    $query->havingRaw("SUM(" . $campaignFilter->filterField . ") " . $campaignFilter->filterOperator . " ?", [$campaignFilter->filterValue]);
                }
            }

            $campaigns = $query->groupBy('campaigns.name', 'campaigns.id')
                               ->orderBy($campaignFilter->sortField, $campaignFilter->sortDirection)
                               ->paginate(30);

            return $campaigns;

        } catch (\Exception $e) {
            Log::error('Error fetching campaigns: ' . $e->getMessage(), [
                'exception' => $e,
                'filter' => $campaignFilter,
            ]);
            throw $e;
        }
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $campaign = Campaign::create([
                'name' => $validated['name'],
            ]);

            return ['status' => 201, 'msg' => 'success'];
        } catch (ValidationException $e) {
            return [ 'status' => 422, 'msg' => 'The given data was invalid'];
        } catch (Exception $e) {
            Log::error('Something went wrong:' . $e->getMessage(), [
                'exception' => $e,
                'campaign_name' => $request->input('name')
            ]);
            return [ 'status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()];
        }
    }
}
