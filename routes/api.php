<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InvoiceController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Api\Customer\DashboardCustomer;
use App\Http\Controllers\Api\Customer\InvoiceCustomer;
use App\Http\Controllers\Api\Customer\RegisterController;
use App\Http\Controllers\Api\Customer\ReviewController;
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
  Route::post('/login', [AuthController::class, 'index', ['as' => 'admin']]);

  // * route group with middleware "auth:api_admin"
  Route::group(['middleware' => 'auth:api_admin'], function () {

    // * get data user
    Route::get('/user', [AuthController::class, 'getUser', ['as' => 'admin']]);

    // * refresh token
    Route::get('/refresh', [AuthController::class, 'refreshToken', ['as' => 'admin']]);

    // * logout
    Route::post('/logout', [AuthController::class, 'logout', ['as' => 'admin']]);

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


//  * group route with prefix "customer"
Route::prefix('customer')->group(function () {

  // * route register
  Route::post('/register', [RegisterController::class, 'store'], ['as' => 'customer']);

  // * route login
  Route::post('/login', [CustomerAuthController::class, 'index'], ['as' => 'customer']);

  // * group route with middleware "auth:api_customer"
  Route::group(['middleware' => 'auth:api_customer'], function () {

    // * data user
    Route::get('/user', [CustomerAuthController::class, 'getUser'], ['as' => 'customer']);

    //  * refresh token JWT
    Route::get('/refresh', [CustomerAuthController::class, 'refreshToken'], ['as' => 'customer']);

    //  * logout
    Route::post('/logout', [CustomerAuthController::class, 'logout'], ['as' => 'customer']);

    // * dashboard customer
    Route::get('/dashboard', [DashboardCustomer::class, 'index'], ['as' => 'customer']);

    // * invoice dashboard customer
    Route::apiResource('/invoices', InvoiceCustomer::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'customer']);

    // * review customer
    Route::post('/reviews', [ReviewController::class, 'store'], ['as' => 'customer']);
  });
});
