<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PushNotification;
use App\Http\Controllers\MessageController;
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
    Route::post('account/change_state', [ServiceController::class, "edit_state"])->name("api:edit_state");
    Route::post('account/gallary/add', [ServiceController::class, "add_to_gallary"])->name("api:add_to_gallary");
    Route::post('account/gallary/remove', [ServiceController::class, "remove_from_gallary"])->name("api:remove_from_gallary");
    Route::post('service/rate', [ServiceController::class, "rate_service"])->name("api:rate_service");
    Route::post('service/create', [ServiceController::class, "create_service"])->name("api:create_service");
    Route::post('service/edit', [ServiceController::class, "edit_service"])->name("api:edit_service");
    
    Route::post('service/order', [ServiceController::class, "order_service"])->name("api:order_service");
    Route::post('service/order/update', [ServiceController::class, "update_order"])->name("api:update_order");
    Route::get('user/orders', [ServiceController::class, "get_orders"])->name("api:get_orders");
    Route::get('user/order', [ServiceController::class, "get_order"])->name("api:get_order");
    Route::get('user/services', [ServiceController::class, "get_my_services"])->name("api:get_my_services");
    Route::get('user/notifications', [ServiceController::class, "get_notifications"])->name("api:get_notifications");
    Route::get('user/balance', [ServiceController::class, "get_balance"])->name("api:get_balance");
    Route::post('user/balance/withdraw', [ServiceController::class, "withdraw_balance"])->name("api:withdraw_balance");
    Route::get('user/balance/withdraw/requests', [ServiceController::class, "check_withdraw_status"])->name("api:check_withdraw_status");

    Route::get('service/orders', [ServiceController::class, "get_service_orders"])->name("api:get_service_orders");

    Route::post('service/order/complete', [ServiceController::class, "complete_order"])->name("api:complete_order");
    Route::post('service/order/cancel', [ServiceController::class, "cancel_order"])->name("api:cancel_order");

    Route::post('coupons/check', [ServiceController::class, "check_coupon"])->name("api:check_coupon");
    

    // chats
    Route::get('chat', [MessageController::class, "get_chats"])->name("api:get_chats");
    Route::post('chat/new_chat', [MessageController::class, "intiate_chat"])->name("api:intiate_chat");

});

// No auth
Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::get('categories', [CategoryController::class, "index"])->name("api:get_categories");
    Route::get('subcategories', [CategoryController::class, "subcategories"])->name("api:get_subcategories");
    Route::get('services', [ServiceController::class, "get_services"])->name("api:get_services");
    Route::get('services/category/{category_id}', [ServiceController::class, "get_category_services"])->name("api:get_category_services");
    Route::get('services/{id}', [ServiceController::class, "get_service"])->name("api:get_single_service");
    Route::get('service/{user_id}/gallary', [AuthController::class, "get_gallary"])->name("api:get_gallary");
    Route::get('users/{id}', [ServiceController::class, "get_user"])->name("api:get_user");

    Route::post('chat/update_chat', [MessageController::class, "update_chat"])->name("api:update_chat");
    Route::get('taxes', [ServiceController::class, "check_taxes"])->name("api:check_taxes");
    Route::get('regions', [ServiceController::class, "get_tax_regions"])->name("api:get_tax_regions");


    // Route::post('notification/all', [PushNotification::class, "notify_all"])->name("api:notify_all");
    Route::post('notification/{user_id}', [PushNotification::class, "notify_user"])->name("api:notify_user");
    
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [AuthController::class, "register"])->name("api:register");
    Route::post('sendVerificationCode', [AuthController::class, "sendVerificationCode"])->name("api:sendVerificationCode");
    Route::post('verifyAccount', [AuthController::class, "verifyAccount"])->name("api:verifyAccount");
    Route::post('login', [AuthController::class, "login"])->name("api:login");
    Route::post('logout', [AuthController::class, "logout"])->name("api:logout");
    Route::post('refresh', [AuthController::class, "refresh"])->name("api:refresh");
    Route::post('me', [AuthController::class, "me"])->name("api:me");

    Route::post('password/reset/code', [SmsPasswordResetController::class, 'sendResetCode']);  // Send SMS reset code
    Route::post('password/reset', [SmsPasswordResetController::class, 'resetPassword']);  

});
