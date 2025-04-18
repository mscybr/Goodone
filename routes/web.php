<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/admin/coupon', [AdminController::class, "create_coupon"])->name("admin_create_coupon");
Route::post('/admin/coupon', [AdminController::class, "store_coupon"])->name("admin_store_coupon");
Route::get('/admin/coupon/delete', [AdminController::class, "delete_coupon"])->name("admin_delete_coupon");

Route::get('/admin/withdrawals', [AdminController::class, "withdraw_requests"])->name("admin_withdraw_requests");
Route::get('/admin/withdrawals/{withdraw_request}/accept', [AdminController::class, "accept_withdraw_request"])->name("admin_accept_withdraw_requests");
Route::get('/admin/withdrawals/{withdraw_request}/reject', [AdminController::class, "reject_withdraw_request"])->name("admin_reject_withdraw_requests");

Route::get('/admin/category', [AdminController::class, "create_category"])->name("admin_create_category");
Route::post('/admin/category', [AdminController::class, "store_category"])->name("admin_store_category");
Route::get('/admin/category/delete', [AdminController::class, "delete_category"])->name("admin_delete_category");

Route::get('/admin/subcategory', [AdminController::class, "create_subcategory"])->name("admin_create_subcategory");
Route::post('/admin/subcategory', [AdminController::class, "store_subcategory"])->name("admin_store_subcategory");
Route::get('/admin/subcategory/delete', [AdminController::class, "delete_subcategory"])->name("admin_delete_subcategory");

Route::get('/admin/settings', [AdminController::class, "get_app_settings"])->name("admin_get_app_settings");
Route::post('/admin/settings/edit', [AdminController::class, "edit_app_settings"])->name("admin_edit_app_settings");

Route::get('/admin/default_images', [AdminController::class, "get_default_images"])->name("admin_get_default_images");
Route::post('/admin/default_images/edit', [AdminController::class, "edit_default_images"])->name("admin_edit_default_images");

Route::get('/admin/region_taxes', [AdminController::class, "create_region_tax"])->name("admin_create_region_tax");
Route::post('/admin/region_taxes', [AdminController::class, "store_region_tax"])->name("admin_store_region_tax");
Route::get('/admin/region_taxes/delete', [AdminController::class, "delete_region_tax"])->name("admin_delete_region_tax");

