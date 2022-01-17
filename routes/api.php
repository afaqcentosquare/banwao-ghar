<?php

use App\Http\Controllers\Api\ApiController;
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

Route::get('getparentcategories', [ApiController::class, 'parentCategories']);

Route::post('registercustomer', [ApiController::class, 'registerCustomer']);

Route::get('getchildcategories/{category_id}', [ApiController::class, 'getChildCategories']);
Route::get('gethomedata', [ApiController::class, 'getHomeData']);
Route::get('getspecificproduct/{id}', [ApiController::class, 'getSpecificProduct']);
Route::get('getcategoryproducts/{id}', [ApiController::class, 'getCategoryProducts']);
Route::post('order', [ApiController::class, 'order']);
Route::get('getallorders/{customer_id}', [ApiController::class, 'getAllOrders']);
Route::get('getorderbyid/{order_id}', [ApiController::class, 'getOrderById']);
Route::get('/clear/route', function () {
    \Artisan::call('route:clear');
});


