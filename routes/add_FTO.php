<?php

use App\Http\Controllers\AddFTOController;
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


// Delay Compensation FTO Route 


Route::get('/add_delay_FTO', [AddFTOController::class, 'viewDelayFTO']);
Route::get('/add_delay_FTO/view_form', [AddFTOController::class, 'delay_view_form']);
Route::get('/add_delay_FTO/add_fto', [AddFTOController::class, 'delay_add_fto']);
Route::get('/add_delay_FTO/submit_fto', [AddFTOController::class, 'delay_submit_fto']);
// Unemployement Allowance FTO Route

Route::get('/add_unemp_FTO', [AddFTOController::class, 'viewUnempFTO']);
Route::get('/add_unemp_FTO/view_form', [AddFTOController::class, 'unemp_view_form']);
Route::get('/add_unemp_FTO/add_fto', [AddFTOController::class, 'unemp_add_fto']);
Route::get('/add_unemp_FTO/submit_fto', [AddFTOController::class, 'unemp_submit_fto']);
