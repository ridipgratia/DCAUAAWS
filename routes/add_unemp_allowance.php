<?php

use App\Http\Controllers\UnemployeAllowanceController;
use App\Http\Controllers\UnemployeAllowanceFromListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/unemploye_allowance', [UnemployeAllowanceController::class, 'index']);
Route::post('/add_unemploye_allowance', [UnemployeAllowanceController::class, 'create']);
Route::get('/unemp_alowance_form_list', [UnemployeAllowanceFromListController::class, 'create']);
Route::get('/unemp_alowance_form_list/edit_form', [UnemployeAllowanceFromListController::class, 'editFormMethod']);
Route::get('/unemp_alowance_form_list/delete_form', [UnemployeAllowanceFromListController::class, 'deleteFormMethod']);
Route::get('/unemp_alowance_form_list/form_list', [UnemployeAllowanceFromListController::class, 'form_list']);
Route::get('/unemp_alowance_form_list/form_data', [UnemployeAllowanceFromListController::class, 'form_list_data']);
Route::post('/unemp_alowance_form_list/search_form_date', [UnemployeAllowanceFromListController::class, 'search_form_date']);

// Submit Edit Reject Form Data
Route::post('/unemp_alowance_form_list/update_edit_form', [UnemployeAllowanceFromListController::class, 'updateEditForm']);
