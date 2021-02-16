<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
// });

Route::prefix('oapi/v1')->group(function () {
   Route::get('/status', function (Request $request) {
      return response()->success();
   });

   Route::post('/login', [AuthController::class, 'login']);
   Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('api/v1')->middleware('auth:api')->group(function () {
   Route::get('/me', function (Request $request) {
      return response()->success($request->user());
   });

   Route::get('/transaction', [TransactionController::class, 'getTransactionList']);
   Route::post('/transaction', [TransactionController::class, 'makeAuthTransaction']);
});

Route::prefix('/transaction')->group(function () {
   Route::post('/', [TransactionController::class, 'makeTransaction']);
});