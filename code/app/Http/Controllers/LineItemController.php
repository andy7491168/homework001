<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineItemService;
class LineItemController extends Controller
{
    protected $lineItemService;
    public function __construct(LineItemService $lineItemService)
    {
        $this->lineItemService = $lineItemService;
    }   
    public function updateAdjustments(Request $request)
    {
        $result = $this->lineItemService->updateAdjustments($request);

        return response()->json(['msg'=>$result['msg']],  $result['status']); 
    }

    public function add(Request $request)
    {
        $result = $this->lineItemService->add($request);

        return response()->json(['msg'=>$result['msg']],  $result['status']); 
    }
}
