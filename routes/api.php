<?php

use App\Http\Controllers\ApiController\LeadControllerApi;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/checking", function(){
    dd("hi");
});

Route::post("login", function () {
    dd("thi this");
});

Route::group(['prefix' => '/v1'], function () {

    /******* Team member apis starts ******/
    Route::group(['prefix'=>'/team-member'], function(){
        Route::post("login", [UserController::class, 'loginUsersUsingOTP']);
        Route::post('verifyotp', [UserController::class, 'verifyUserMobile']);
        Route::post("createincompletelead", [LeadControllerApi::class, 'incompleteLeads']);
        Route::get("getallleads", [LeadControllerApi::class, 'getAllLeads']);
    });
    /******* Team member apis ends ******/


    /* payment with paytm */
    Route::post('paytm-payment',[PaymentController::class, 'initiatePayment']);
    Route::get('transaction-status-paytm', [PaymentController::class, 'transactionStatus']);
});
