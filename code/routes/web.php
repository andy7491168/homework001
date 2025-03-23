<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CampaignController;
use App\Http\Livewire\CampaignDetailController;
use App\Http\Controllers\ChartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', CampaignController::class);
Route::get('/campaign', CampaignController::class);
Route::get('/campaign/comment/{id}', [CampaignController::class,'showComments']);
Route::post('/campaign/comment/{id}', [CampaignController::class,'editComments']);

Route::get('/campaign/detail/{campaignID}', CampaignDetailController::class);
Route::get('/chart/{id}', [ChartController::class, 'show']);