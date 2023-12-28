<?php




use App\Http\Controllers\state\ListCeoController;
use App\Http\Controllers\state\AddCEOController;
use App\Http\Controllers\state\AddPOController;
use App\Http\Controllers\state\DelayCompensationController;
use App\Http\Controllers\state\ListPoController;
use App\Http\Controllers\state\SendNotificationController;
use App\Http\Controllers\state\UnempAllowController;
use App\Http\Controllers\StateController;
use App\View\Components\SendNotificationComponent;
use Illuminate\Queue\ListenerOptions;
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

// CheckAuth Middleware  For check is user logged in

// State Dash Board Page 

Route::group(['middleware' => ['CheckStateAuth']], function () {


    Route::get('/state_dash', [StateController::class, 'index']);

    // Add CEO Pd User By State Load Form
    Route::get('/add_ceo', [AddCEOController::class, 'index']);
    // Add CEO PD User By State Form Submit
    Route::post('/add_ceo', [AddCEOController::class, 'add_user']);
    // Add PO User By State Load Form 
    Route::get('/add_po', [AddPOController::class, 'index']);
    // Add PO User By State Form Submit
    Route::post('/add_po', [AddPOController::class, 'add_user']);
    // List CEO/PD  Users 
    Route::get('/list_ceo', [ListCeoController::class, 'list_ceo']);
    // List PO Data
    Route::get('/list_po', [ListPoCOntroller::class, 'list_po']);
    // Fetch CEO/PD User List For Datatable 
    Route::get('/list_ceo/for_table', [ListCeoController::class, 'for_table']);
    // Fetch PO User List For Datatable 
    Route::get('/list_po/for_table', [ListPoController::class, 'for_table']);
    // View User CEO Data
    Route::get('/list_ceo/view_data', [ListCeoController::class, 'view_data']);
    // View User PO Data
    Route::get('/list_po/view_data', [ListPoController::class, 'view_data']);
    // Reset CEO User Password 
    Route::get('/list_ceo/reset_pass', [ListCeoController::class, 'reset_pass']);
    // Reset PO User Password
    Route::get('/list_po/reset_pass', [ListPoController::class, 'reset_pass']);
    // Remove CEO PD User 
    Route::get('/list_ceo/remove_user', [ListCeoController::class, 'remove_user']);
    // Remove PO User
    Route::get('/list_po/remove_user', [ListPoController::class, 'remove_user']);
    // Edit CEO PD User
    Route::get('/list_ceo/edit_user', [ListCeoController::class, 'edit_user']);
    // Edit PO User
    Route::get('/list_po/edit_user', [ListPoController::class, 'edit_user']);
    // Edit CEO PD User Submit 
    Route::post('/list_ceo/edit_user_submit', [ListCeoController::class, 'edit_user_submit']);
    // Edit Po User submit
    Route::post('/list_po/edit_user_submit', [ListPoController::class, 'edit_user_submit']);

    // Get Block By Change District 
    Route::get('/list_po/get_blocks', [AddPOController::class, 'get_blocks']);
    // All Delay Compensation Form List
    Route::get('/delay_compensation/all_list', [DelayCompensationController::class, 'all_list']);
    // All Unemploye Allowance Form List
    // Get Blocks By District Code 
    Route::get('/delay_compensation/get_blocks', [DelayCompensationController::class, 'get_blocks']);
    // Get GPs By Block Id
    Route::get('/delay_compensation/get_gps', [DelayCompensationController::class, 'get_gps']);
    // Get Delay Compensation Form Lists
    Route::get('/delay_compensation/get_delay_com', [DelayCompensationController::class, 'get_delay_com']);
    // View All Form Data By ID
    Route::get('/delay_compensation/view_form_by_id', [DelayCompensationController::class, 'view_form_by_id']);
    // Search By District, Block,GP and Dates
    Route::post('/delay_compensation/search_query', [DelayCompensationController::class, 'search_query']);
    // All Unemploye Allowance Form List
    Route::get('/unemp_allow/all_list', [UnempAllowController::class, 'all_list']);
    // Get Blocks By District Code 
    Route::get('/unemp_allow/get_blocks', [UnempAllowController::class, 'get_blocks']);
    // Get GPs By Block Id
    Route::get('/unemp_allow/get_gps', [UnempAllowController::class, 'get_gps']);
    // Get Delay Compensation Form Lists
    Route::get('/unemp_allow/get_unemp_allow', [UnempAllowController::class, 'get_unemp_allow']);
    // View All Form Data By ID
    Route::get('/unemp_allow/view_form_by_id', [UnempAllowController::class, 'view_form_by_id']);
    // Search By District, Block,GP and Dates
    Route::post('/unemp_allow/search_query', [UnempAllowController::class, 'search_query']);
    // Send Notification View Route
    Route::get('/send_notification', [SendNotificationController::class, 'index']);
    // Store Notification
    Route::post('/send_notify_form', [SendNotificationController::class, 'store_notification']);
    // View Particular Notification 
    Route::get('/view_notification', [SendNotificationController::class, 'view_notification']);

    // Remove Notification
    Route::get('/remove_notification', [SendNotificationController::class, 'remove_notification']);
    // Delay Compensation Pending List
    Route::get('/delay_compensation/pending_list', [DelayCompensationController::class, 'pending_list']);
    // Unemploye Compensation Pending List
    Route::get('/unemp_allow/pending_list', [UnempAllowController::class, 'pending_list']);
    // Get Delay Pending List data
    Route::get('/delay_compensation/pending_list_data', [DelayCompensationController::class, 'pending_list_data']);
    // Get Unemploye Pending List Data
    Route::get('/unemp_allow/pending_list_data', [UnempAllowController::class, 'pending_list_data']);
    // Get Search Pending Data Delay 
    Route::post('/delay_compensation/pending_filter_list', [DelayCompensationController::class, 'pending_filter_data']);
    // Get Search Pending data Unemploye 
    Route::post('/unemp_allow/pending_filter_list', [UnempAllowController::class, 'pending_filter_list']);

    // Delay Approved Pending Route 
    Route::get('/delay_compensation/approved_pending_form', [DelayCompensationController::class, 'approved_pending_form']);
    //Unemployed pending route
    Route::get('/unemp_allow/unemp_pending_form', [UnempAllowController::class, 'unemp_pending_form']);
});
