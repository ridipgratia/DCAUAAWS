<?php

namespace App\Http\Controllers;

use App\MyMethod\DelayEmpForm;
use App\MyMethod\UsedMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $emp_code = Auth::user()->login_id;
        $delay_data = DB::table('add_dc')->where('submited_by', $emp_code)->count();
        $unemploye_data = DB::table('add_unemp_allowance')->where('submited_by', $emp_code)->count();
        $or_where = ['block_id', NULL];
        $auth_district = Auth::user()->district;
        $auth_block = Auth::user()->block;
        $notifications = UsedMethod::getNotificationBlock($auth_district, $auth_block, $or_where);
        $new_notify = UsedMethod::getNewNotify($notifications, $auth_district, $auth_block);
        $check_form_pendding = DelayEmpForm::checkFormPending('add_dc');
        $check_unemp_form = DelayEmpForm::checkFormPending('add_unemp_allowance');
        return view('block_dash', [
            'delay_form_list' => $delay_data,
            'unemp_allowance_form_list' => $unemploye_data,
            'new_notify' => $new_notify,
            'check_form_pendding' => $check_form_pendding,
            'check_unemp_form' => $check_unemp_form
        ]);
    }
}
