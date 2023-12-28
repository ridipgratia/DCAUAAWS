<?php

namespace App\Http\Controllers\district;

use App\Http\Controllers\Controller;
use App\MyMethod\DistrictMethod;
use App\MyMethod\UsedMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{
    public function index()
    {
        $emp_district = Auth::user()->district;
        $delay_data = DistrictMethod::getCountPendingForm('add_dc', 'delay_form_status');
        $unemploye_data = DistrictMethod::getCountPendingForm('add_unemp_allowance', 'unemp_form_status');
        $auth_district = Auth::user()->district;
        $auth_block = NULL;
        $or_where = ['district_id', 999];
        $notifications = UsedMethod::getNotifications(Auth::user()->district, $or_where);
        $new_notify = UsedMethod::getNewNotify($notifications, $auth_district, $auth_block);
        return view('district.district_dashbaord', [
            'delay_form_list' => $delay_data,
            'unemp_allowance_form_list' => $unemploye_data,
            'new_notify' => $new_notify
        ]);
    }
}
