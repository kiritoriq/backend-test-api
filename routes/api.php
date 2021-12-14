<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
], function ($router) {
    $router->post('/login', [AuthController::class, 'loginAction']);
});

Route::group([
    'middleware' => ['jwt.verify']
], function($router) {
   $router->post('/logout', [AuthController::class, 'logoutAction']);
   $router->get('/report-by-merchant', [ReportController::class, 'getReportByMerchant'])->name('report.merchant');
   $router->get('/report-by-outlet', [ReportController::class, 'getReportByOutlet'])->name('report.outlet');
});
