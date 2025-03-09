<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\LineItem;
use App\DTOs\LineFilter;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LineItemService
{
    public function getLineItems(LineFilter $lineFilter,int $campaignId)
    {
        try {
            $query = campaign::join('line_items', 'campaigns.id', '=', 'line_items.campaign_id')
            ->selectRaw('
                line_items.id as line_item_id, 
                campaigns.id as campaign_id, 
                campaigns.name as compaign_name, 
                line_items.name as line_item_name, 
                booked_amount, 
                actual_amount, 
                adjustments, 
                (actual_amount + adjustments) as invoice_amount
            ')
            ->where('campaign_id', '=',  $campaignId )
            ->where('line_items.name', 'like', '%' . $lineFilter->search . '%') ;

            if ($lineFilter->filterValue !== null && $lineFilter->filterValue !== '' ) {
                $allowedFields = ['booked_amount', 'actual_amount', 'adjustments'];
                $allowedOperators = ['>=', '<='];

                if ($lineFilter->filterField==='invoice_amount' && in_array($lineFilter->filterOperator, $allowedOperators)){
                    $query->whereRaw("(actual_amount + adjustments) {$lineFilter->filterOperator} ?", [$lineFilter->filterValue]);
                }else if (in_array($lineFilter->filterField, $allowedFields) && in_array($lineFilter->filterOperator, $allowedOperators)) {
                    $query->where($lineFilter->filterField,$lineFilter->filterOperator,$lineFilter->filterValue);
                }
            }

            $lineItems = $query->orderBy($lineFilter->sortField, $lineFilter->sortDirection)
                            ->paginate(30);
                        
            return $lineItems;
        } catch (QueryException $e) {
            Log::error('Database query error in getLineItems: ' . $e->getMessage(), [
                'exception' => $e,
                'lineFilter' => $lineFilter,
                'campaignId' => $campaignId,
            ]);
           throw $e;
        } catch (Exception $e) {
            Log::error('Database query error in getLineItems: ' . $e->getMessage(), [
                'exception' => $e,
                'lineFilter' => $lineFilter,
                'campaignId' => $campaignId,
            ]);
            throw $e;
        }
    } 

    public function updateAdjustments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'line_item_id' => 'required|integer',
                'campaign_id' => 'required|integer',
                'adjustments' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return [ 'status' => 400, 'msg' => 'The given data was invalid'];
            }

            $lineItemId = $request->input('line_item_id');
            $campaignId = $request->input('campaign_id');
            $adjustments = $request->input('adjustments');

            $record = LineItem::where('id', $lineItemId)
                ->where('campaign_id', $campaignId)
                ->first();

            if (!$record) {
                return ['status' => 400, 'msg' => 'No matching data found'];
            }

            $record->adjustments = $adjustments;
            $record->save();
            return [ 'status' => 200, 'msg' => 'success'];
        } catch (Exception $e) {
            Log::error('Something went wrong:' . $e->getMessage(), [
                'exception' => $e,
                'line_item_id' => $request->input('line_item_id'),
                'campaign_id' => $request->input('campaign_id'),
                'adjustments' => $request->input('adjustments'),
            ]);
            return [ 'status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function add(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'campaign_id' => 'required|integer',
                'booked_amount' => 'required|numeric',
                'actual_amount' => 'required|numeric',
            ]);

            $lineItem = LineItem::create($validatedData);

            return [ 'status' => 201, 'msg' => 'success'];
        } catch (ValidationException $e) {
            return [ 'status' => 422, 'msg' => 'The given data was invalid'];
        } catch (Exception $e) {
            Log::error('Something went wrong:' . $e->getMessage(), [
                'exception' => $e,
                'line_item_id' => $request->input('line_item_id'),
                'campaign_id' => $request->input('campaign_id'),
                'booked_amount' => $request->input('booked_amount'),
                'adjustments' => $request->input('adjustments'),
            ]);
            return [ 'status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()];
        }
    }
}
