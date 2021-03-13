<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ReportController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('bank/create', [ApiController::class, 'createBank']);
Route::any('customer/create', [ApiController::class, 'createCustomer']);
Route::any('bank/branch/create', [ApiController::class, 'createBankBranch']);
Route::any('money/transfer', [ApiController::class, 'moneyTransfer']);

Route::any('findByBranchHigherBalance', [ReportController::class, 'findByBranchHigherBalance']);
Route::any('atLeastTwoCustomerWith50k', [ReportController::class, 'atLeastTwoCustomerWith50k']);
