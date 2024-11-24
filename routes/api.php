<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\SmsPasswordResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth
Route::group([

    'middleware' => ['api', 'auth:api'],

], function ($router) {

    Route::post('account/edit', [AuthController::class, "edit"])->name("api:edit");
    Route::post('account/gallary/add', [AuthController::class, "add_to_gallary"])->name("api:add_to_gallary");
    Route::post('account/gallary/remove', [AuthController::class, "remove_from_gallary"])->name("api:remove_from_gallary");

});

// No auth
Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::get('categories', [CategoryController::class, "index"])->name("api:get_categories");
    Route::get('services', [ServiceController::class, "get_services"])->name("api:get_services");
    Route::get('services/{category_id}', [ServiceController::class, "get_category_services"])->name("api:get_category_services");
    Route::get('service/{user_id}/gallary', [AuthController::class, "get_gallary"])->name("api:get_gallary");

});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [AuthController::class, "register"])->name("api:register");
    Route::post('login', [AuthController::class, "login"])->name("api:login");
    Route::post('logout', [AuthController::class, "logout"])->name("api:logout");
    Route::post('refresh', [AuthController::class, "refresh"])->name("api:refresh");
    Route::post('me', [AuthController::class, "me"])->name("api:me");

    Route::post('password/reset/code', [SmsPasswordResetController::class, 'sendResetCode']);  // Send SMS reset code
    Route::post('password/reset', [SmsPasswordResetController::class, 'resetPassword']);  

});
