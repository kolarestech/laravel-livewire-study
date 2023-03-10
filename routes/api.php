<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DenounceController;
use App\Http\Controllers\FollowerController;
use App\Jobs\CustomerAccountCreated as JobsCustomerAccountCreated;
use App\Jobs\CustomerVerifyToken;
use Illuminate\Support\Facades\Route;

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

Route::get('teste', function () {
    //JobsCustomerAccountCreated::dispatch('teste@test.com')->onQueue('queue_email');
    (new CustomerVerifyToken("teste@test.com", "1234"))->dispatch("teste@test.com", "1234")->onQueue("queue_email");
    return response()->json(["message" => "success"]);
});