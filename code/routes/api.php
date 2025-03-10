<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineItemController;
use App\Http\Controllers\CampaignController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/campaign/export', [CampaignController::class, 'export']);
Route::post('/v1/line-item/', [LineItemController::class, 'add']);
Route::patch('/v1/line-item/adjustments', [LineItemController::class, 'updateAdjustments']);
Route::post('/v1/campaign', [CampaignController::class, 'add']);

