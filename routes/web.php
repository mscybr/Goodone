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

Route::get('/admin/category', [AdminController::class, "create_category"])->name("admin_create_category");
Route::post('/admin/category', [AdminController::class, "store_category"])->name("admin_store_category");
Route::get('/admin/category/delete', [AdminController::class, "delete_category"])->name("admin_delete_category");
