<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InvoiceController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//   return $request->user();
// });


//  * group route with prefix "admin"
Route::prefix('admin')->group(function () {

  // * route login
  Route::post('/login', [LoginController::class, 'index', ['as' => 'admin']]);

  // * route group with middleware "auth:api_admin"
  Route::group(['middleware' => 'auth:api_admin'], function () {

    // get data user
    Route::get('/user', [LoginController::class, 'getUser', ['as' => 'admin']]);

    // refresh token
    Route::get('/refresh', [LoginController::class, 'refreshToken', ['as' => 'admin']]);

    // logout
    Route::get('/logout', [LoginController::class, 'logout', ['as' => 'admin']]);

    // * Route Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index', ['as' => 'admin']]);
    Route::get('/customers', [CustomerController::class, 'index', ['as' => 'admin']]);

    Route::apiResource('/categories', CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
    Route::apiResource('/products', ProductController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
    Route::apiResource('/invoices', InvoiceController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
    Route::apiResource('/sliders', SliderController::class, ['except' => ['show', 'update', 'create', 'edit'], 'as' => 'admin']);
    Route::apiResource('/users', UserController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
  });
});
