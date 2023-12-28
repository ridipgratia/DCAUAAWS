<?php

namespace App\MyMethod;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DistrictMethod
{
    public static function getCountPendingForm($table, $sub_table)
    {
        $count = DB::table($table . ' as main_table')
            ->select(
                'main_table.*',
                'sub_table.*'
            )
            ->where('main_table.district_id', Auth::user()->district)
            ->where('sub_table.district_approval', '1')
            ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
            ->count();
        return $count;
    }
    public static function GetFormList($table, $sub_table, $columns)
    {
        $district_code = Auth::user()->district;
        $lists = DB::table($table . ' as main_table')
            ->where('main_table.district_id', $district_code)
            ->where('sub_table.district_approval', '1')
            ->select(
                'main_table.' . $columns[0],
                'main_table.' . $columns[1],
                'main_table.' . $columns[2],
                'main_table.request_id',
                'main_table.date_of_submit',
                'main_table.approval_status',
                'sub_table.*',
                'main_table.id as main_id'
            )
            ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
            ->get();
        return $lists;
    }
    public static function checkIsDateValid($date, $table)
    {
        $check_date = DB::table($table)->where('district_id', Auth::user()->district)->where('date_of_submit', $date)->get();
        if (count($check_date) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function getFromdata($date, $table)
    {
        $form_data = DB::table($table)->where('district_id', Auth::user()->district)->where('date_of_submit', $date)->get();
        return $form_data;
    }
    public static function searchByDate($table, $form_date, $to_date)
    {
        $message = null;
        $status = null;
        if ($form_date == null || $to_date == "") {
            $status = 400;
            $message = "Please Select Form Submission dates ";
        } else {
            if ($form_date <= $to_date) {
                $period = new DatePeriod(
                    new DateTime($form_date),
                    new DateInterval('P1D'),
                    new DateTime($to_date)
                );
                $form_to_date = array();
                foreach ($period as $key => $value) {
                    array_push($form_to_date, $value->format('Y-m-d'));
                }
                $date_one = date($to_date, strtotime('+1 day'));
                array_push($form_to_date, $date_one);
                $form_date_his = array();
                foreach ($form_to_date as $dates) {
                    if (DistrictMethod::checkIsDateValid($dates, $table)) {
                        $form_data = DistrictMethod::getFromdata($dates, $table);
                        array_push($form_date_his, $form_data);
                    }
                }
                $message = $form_date_his;
                $status = 200;
            } else {
                $message = "Select Valid Dates ";
                $status = 400;
            }
        }
        return [$status, $message];
    }
    // Get District Name
    public static function getDistrictName()
    {
        $district_name = DB::table('districts')
            ->where('district_code', Auth::user()->district)
            ->select('district_name')
            ->get();
        return $district_name[0]->district_name;
    }
    // Get All Block Names 
    public static function getBlocksName()
    {
        $block_names = DB::table('blocks')
            ->where('district_id', Auth::user()->district)
            ->orderBy('block_name', 'asc')
            ->get();
        return $block_names;
    }
    // Get Gp Name By Block
    public static function getGpByBlock($block_id)
    {
        $gp_names = DB::table('gram_panchyats')
            ->where('block_id', $block_id)
            ->select('gram_panchyat_id', 'gram_panchyat_name')
            ->orderBy('gram_panchyat_name', 'asc')
            ->get();
        return $gp_names;
    }
    // Serach By Block,Gp And dates 
    public static function searchByBlockGpDates($form_date, $to_date, $block_name, $gp_name, $table, $sub_table, $status_key)
    {
        $status = null;
        $message = null;
        if ($form_date === null && $to_date === null && $block_name === null && $gp_name === null) {
            $status = 200;
            $result = DB::table($table . ' as main_table')
                ->select(
                    'main_table.*',
                    'sub_table.*',
                    'main_table.id as main_id'
                )
                ->where('main_table.district_id', Auth::user()->district)
                ->where('sub_table.district_approval', $status_key)
                ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                ->get();
            $message = array($result);
        } else {
            if (($form_date === null && $to_date !== null) || ($form_date !== null && $to_date === null)) {
                $status = 400;
                $message = "Select Both Dates";
            } else {
                if ($form_date !== null && $block_name !== null && $gp_name !== null) {
                    if ($form_date <= $to_date) {
                        $form_to_date = DistrictMethod::getPeriodDates($form_date, $to_date);
                        $form_date_his = array();
                        foreach ($form_to_date as $dates) {
                            if (DistrictMethod::checkIsDateAvai($table, $dates)) {
                                $form_data = DB::table($table . ' as main_table')
                                    ->select(
                                        'main_table.*',
                                        'sub_table.*',
                                        'main_table.id as main_id'
                                    )
                                    ->where('main_table.district_id', Auth::user()->district)
                                    ->where('sub_table.district_approval', $status_key)
                                    ->where('main_table.date_of_submit', $dates)
                                    ->where('main_table.gp_id', $gp_name)
                                    ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                                    ->get();
                                array_push($form_date_his, $form_data);
                            }
                        }
                        // $message = "All Filter data";
                        $status = 200;
                        $message = $form_date_his;
                    } else {
                        $status = 400;
                        $message = "Select A Valid Dates";
                    }
                } else {
                    if ($form_date !== null && $block_name !== null) {
                        if ($form_date <= $to_date) {
                            $form_to_date = DistrictMethod::getPeriodDates($form_date, $to_date);
                            $form_date_his = array();
                            foreach ($form_to_date as $dates) {
                                if (DistrictMethod::checkIsDateAvai($table, $dates)) {
                                    $form_data = DB::table($table . ' as main_table')
                                        ->select(
                                            'main_table.*',
                                            'sub_table.*',
                                            'main_table.id as main_id'
                                        )
                                        ->where('main_table.district_id', Auth::user()->district)
                                        ->where('sub_table.district_approval', $status_key)
                                        ->where('main_table.block_id', $block_name)
                                        ->where('main_table.date_of_submit', $dates)
                                        ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                                        ->get();
                                    array_push($form_date_his, $form_data);
                                }
                            }
                            $status = 200;
                            $message = $form_date_his;
                        } else {
                            $status = 400;
                            $message = "Select A Valid Dates";
                        }
                    } else {
                        if ($block_name !== null) {
                            if ($gp_name !== null) {
                                $result = DB::table($table . ' as main_table')
                                    ->select(
                                        'main_table.*',
                                        'sub_table.*',
                                        'main_table.id as main_id'
                                    )
                                    ->where('main_table.district_id', Auth::user()->district)
                                    ->where('sub_table.district_approval', $status_key)
                                    ->where('main_table.block_id', $block_name)
                                    ->where('main_table.gp_id', $gp_name)
                                    ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                                    ->get();
                                $message = array($result);
                            } else {
                                $result = DB::table($table . ' as main_table')
                                    ->select(
                                        'main_table.*',
                                        'sub_table.*',
                                        'main_table.id as main_id'
                                    )
                                    ->where('main_table.district_id', Auth::user()->district)
                                    ->where('sub_table.district_approval', $status_key)
                                    ->where('main_table.block_id', $block_name)
                                    ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                                    ->get();
                                $message = array($result);
                            }
                            $status = 200;
                        } else {
                            if ($form_date !== null) {
                                if ($form_date <= $to_date) {
                                    $status = 200;
                                    $form_to_date = DistrictMethod::getPeriodDates($form_date, $to_date);
                                    $form_date_his = array();
                                    foreach ($form_to_date as $dates) {
                                        if (DistrictMethod::checkIsDateAvai($table, $dates)) {
                                            $form_data = DB::table($table . ' as main_table')
                                                ->select(
                                                    'main_table.*',
                                                    'sub_table.*',
                                                    'main_table.id as main_id'
                                                )
                                                ->where('main_table.district_id', Auth::user()->district)
                                                ->where('sub_table.district_approval', $status_key)
                                                ->where('main_table.date_of_submit', $dates)
                                                ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
                                                ->get();
                                            array_push($form_date_his, $form_data);
                                        }
                                    }
                                    $message = $form_date_his;
                                } else {
                                    $status = 400;
                                    $message = "Select A Valid Dates";
                                }
                            }
                        }
                    }
                }
            }
        }
        return [$status, $message];
    }
    // get Period Dates 
    public static function getPeriodDates($form_date, $to_date)
    {
        $period = new DatePeriod(
            new DateTime($form_date),
            new DateInterval('P1D'),
            new DateTime($to_date)
        );
        $form_to_date = array();
        foreach ($period as $key => $value) {
            array_push($form_to_date, $value->format('Y-m-d'));
        }
        $date_one = date($to_date, strtotime('+1 day'));
        array_push($form_to_date, $date_one);
        return $form_to_date;
    }
    public static function checkIsDateAvai($table, $date)
    {
        $check = DB::table($table)
            ->where('district_id', Auth::user()->district)
            ->where('date_of_submit', $date)
            ->get();
        if (count($check) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // Get Approval Form List Data
    public static function loadApprovalData($table, $sub_table)
    {
        $data = DB::table($table . ' as main_table')
            ->select(
                'main_table.*',
                'sub_table.*',
                'main_table.id as main_id'
            )
            ->where('main_table.district_id', Auth::user()->district)
            ->where('sub_table.district_approval', '3')
            ->join($sub_table . ' as sub_table', 'sub_table.form_request_id', '=', 'main_table.request_id')
            ->get();
        return $data;
    }
    // For Delay Form
    public static function viewFormData($table, $delay_form_id)
    {
        $district_code = Auth::user()->district;
        $delay_form_data = DB::table($table)
            ->where('district_id', $district_code)
            ->where('id', $delay_form_id)->get();
        if (count($delay_form_data) == 0) {
            $content = "<p>No data Found</p>";
        } else {
            $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
            //         $content = '<p class="delay_para_head para_head">Work Code Number</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->code_number . '</p>
            // <p class="delay_para_head para_head">MR Number</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->mr_number . '</p>
            // <p class="delay_para_head para_head">Person Responsible For Delay</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->person_delay . '</p>
            // <p class="delay_para_head para_head">Designation Responsible For Delay</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->designation_delay . '</p>
            // <p class="delay_para_head para_head">Recovered Amount</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->recover_amount . '</p>
            // <p class="delay_para_head para_head">Date Amount Recovered</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->date_recover_amount . '</p>
            // <p class="delay_para_head para_head">Date Deposited To Bank</p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->date_deposite_bank . '</p>
            // <p class="delay_para_head para_head">Date of Submited </p>
            // <p class="delay_para para_1">' . $delay_form_data[0]->date_of_submit . '</p>
            // <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '">View Upload Document</button>';
            $content = '<p class="delay_para_head para_head">Work Code Number</p>
                        <p class="delay_para para_1"> ' . $delay_form_data[0]->code_number . ' </p>
                        <p class="delay_para_head para_head">MR Number</p>
                        <p class="delay_para para_1"> ' . $delay_form_data[0]->mr_number . '</p>
                        <p class="delay_para_head para_head">Person Responsible For Delay</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->person_delay . '</p>
                        <p class="delay_para_head para_head">Designation Responsible For Delay</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->designation_delay . '</p>
                        <p class="delay_para_head para_head">Recovered Amount</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->recover_amount . '</p>
                        <p class="delay_para_head para_head">Date Amount Recovered</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->date_recover_amount . '</p>
                        <p class="delay_para_head para_head">Date Deposited To Bank</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->date_deposite_bank . '</p>
                        <p class="delay_para_head para_head">Date of Submited </p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->date_of_submit . '</p>
                        <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '"><i
                                class="fa-solid fa-file"></i></button><button id="approved_district_btn" class="btn btn-primary btn-success"
                                value="' . $delay_form_data[0]->id . '">Accept</button>
                            <button id="reject_district_btn" class="btn btn-primary btn-danger"
                                value="' . $delay_form_data[0]->id . '">Reject</button><div class="d-flex flex-column col-12 mt-4 district_reason_div">
                                <p class="col-md-6 col-8">Reason for rejection</p>
                                <textarea class="form-control col-md-4 mb-2" id="form_reason" name="editor" rows="3" style="width:70%; resize:none;"></textarea>
                               <button class="col-md-4 col-8 btn btn-success mb-2" id="form_reject_btn" value="' . $delay_form_data[0]->id . '">Submit</button>
                               <button class="col-md-4 col-8 btn btn-warning" id="form_reason_cancel" value="' . $delay_form_data[0]->id . '">Cancel</button>
                               </div>';
        }
        return $content;
    }
    // For Unemp Allow
    public static function unempViewFormData($table, $delay_form_id)
    {
        $district_code = Auth::user()->district;
        $delay_form_data = DB::table('add_unemp_allowance')->where('district_id', $district_code)->where('id', $delay_form_id)->get();
        if (count($delay_form_data) == 0) {
            $content = "<p>No data Found</p>";
        } else {
            $img_url = Storage::url($delay_form_data[0]->bank_statement_url);


            $content = '<p class="delay_para_head para_head">Work Card Number</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->card_number . '</p>
            <p class="delay_para_head para_head">Work Demand</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->work_demand . '</p>
            <p class="delay_para_head para_head">Total Day of Unemployement</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->total_day_unemple . '</p>
            <p class="delay_para_head para_head">Person Responsible For Delay</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->person_delay . '</p>
            <p class="delay_para_head para_head">Designation Responsible For Delay</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->designation_delay . '</p>
            <p class="delay_para_head para_head">Recovered Amount</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->recover_amount . '</p>
            <p class="delay_para_head para_head">Date Amount Recovered</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->date_recover_amount . '</p>
            <p class="delay_para_head para_head">Date Deposited To Bank</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->date_deposite_bank . '</p>
            <p class="delay_para_head para_head">Date of Submited </p>
            <p class="delay_para para_1">' . $delay_form_data[0]->date_of_submit . '</p>
            <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '"><i
            class="fa-solid fa-file"></i></button>
    <button id="approved_district_btn" class="btn btn-primary btn-success"
        value="' . $delay_form_data[0]->id . '">Accept</button>
    <button id="reject_district_btn" class="btn btn-primary btn-danger"
        value="' . $delay_form_data[0]->id . '">Reject</button><div class="d-flex flex-column col-12 mt-4 district_reason_div">
        <p class="col-md-6 col-8">Reason for rejection</p>
        <textarea class="form-control col-md-4 mb-2" id="form_reason" name="editor" rows="3" style="width:70%; resize:none;"></textarea>
       <button class="col-md-4 col-8 btn btn-success mb-2" id="form_reject_btn" value="' . $delay_form_data[0]->id . '">Submit</button>
       <button class="col-md-4 col-8 btn btn-warning" id="form_reason_cancel" value="' . $delay_form_data[0]->id . '">Cancel</button>                        
       </div>';
        }
        return $content;
    }
    public static function getRequestID($table, $id)
    {
        $request_id = DB::table($table)
            ->where('district_id', Auth::user()->district)
            ->where('id', $id)
            ->select('request_id')
            ->get();
        return $request_id;
    }
    public static function approvalMethod($main_table, $table, $request_id, $approval_index, $reason)
    {
        $success = false;
        $today = date('Y-m-d');
        try {
            DB::table($table . ' as sub_table')
                ->where('sub_table.form_request_id', $request_id)
                ->where('main_table.district_id', Auth::user()->district)
                ->join($main_table . ' as main_table', 'main_table.request_id', '=', 'sub_table.form_request_id')
                ->update(['sub_table.district_approval' => $approval_index, 'sub_table.district_approval_date' => $today, 'sub_table.district_remarks' => $reason]);
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }
        return $success;
    }
    public static function viewApprovedData($table, $id)
    {
        $form_data = DB::table($table)
            ->where('district_id', Auth::user()->district)
            ->where('id', $id)
            ->get();
        return $form_data;
    }
    public static function viewDelayApprovedData($form_data)
    {
        $content = '';
        if (count($form_data) == 0) {
            $content = '<p>No Data</p>';
        } else {
            $img_url = Storage::url($form_data[0]->bank_statement_url);
            $content = '<p class="delay_para_head para_head">Work Code Number</p>
            <p class="delay_para para_1"> ' . $form_data[0]->code_number . ' </p>
            <p class="delay_para_head para_head">MR Number</p>
            <p class="delay_para para_1"> ' . $form_data[0]->mr_number . '</p>
            <p class="delay_para_head para_head">Person Responsible For Delay</p>
            <p class="delay_para para_1">' . $form_data[0]->person_delay . '</p>
            <p class="delay_para_head para_head">Designation Responsible For Delay</p>
            <p class="delay_para para_1">' . $form_data[0]->designation_delay . '</p>
            <p class="delay_para_head para_head">Recovered Amount</p>
            <p class="delay_para para_1">' . $form_data[0]->recover_amount . '</p>
            <p class="delay_para_head para_head">Date Amount Recovered</p>
            <p class="delay_para para_1">' . $form_data[0]->date_recover_amount . '</p>
            <p class="delay_para_head para_head">Date Deposited To Bank</p>
            <p class="delay_para para_1">' . $form_data[0]->date_deposite_bank . '</p>
            <p class="delay_para_head para_head">Date of Submited </p>
            <p class="delay_para para_1">' . $form_data[0]->date_of_submit . '</p>
            <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '"><i
                    class="fa-solid fa-file"></i></button>';
        }
        return $content;
    }
    public static function viewUnempApprovedData($form_data)
    {
        $content = '';
        if (count($form_data) == 0) {
            $content = '<p>No Data</p>';
        } else {
            $img_url = Storage::url($form_data[0]->bank_statement_url);
            $content = '<p class="delay_para_head para_head">Work Card Number</p>
            <p class="delay_para para_1"> ' . $form_data[0]->card_number . ' </p>
            <p class="delay_para_head para_head">Work Demand</p>
            <p class="delay_para para_1"> ' . $form_data[0]->work_demand . '</p>
            <p class="delay_para_head para_head">Total Day Unemployed</p>
            <p class="delay_para para_1"> ' . $form_data[0]->total_day_unemple . '</p>
            <p class="delay_para_head para_head">Person Responsible For Delay</p>
            <p class="delay_para para_1">' . $form_data[0]->person_delay . '</p>
            <p class="delay_para_head para_head">Designation Responsible For Delay</p>
            <p class="delay_para para_1">' . $form_data[0]->designation_delay . '</p>
            <p class="delay_para_head para_head">Recovered Amount</p>
            <p class="delay_para para_1">' . $form_data[0]->recover_amount . '</p>
            <p class="delay_para_head para_head">Date Amount Recovered</p>
            <p class="delay_para para_1">' . $form_data[0]->date_recover_amount . '</p>
            <p class="delay_para_head para_head">Date Deposited To Bank</p>
            <p class="delay_para para_1">' . $form_data[0]->date_deposite_bank . '</p>
            <p class="delay_para_head para_head">Date of Submited </p>
            <p class="delay_para para_1">' . $form_data[0]->date_of_submit . '</p>
            <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '"><i
                    class="fa-solid fa-file"></i></button>';
        }
        return $content;
    }
    public static function checkApprovalStatus($table, $request_id)
    {
        $status_icon = ['<i class="fa-solid fa-hourglass-half"></i>'];
        $revert_btn = [''];
        $progress_div = '';
        $form_data = DB::table($table)
            ->where('form_request_id', $request_id)
            ->get();
        if (count($form_data) != 0) {
            if ($form_data[0]->state_approval == 2) {
                $status_icon[0] = '<i class="fa-solid fa-xmark"></i>';
                $revert_btn[0] = '<button class="form_edit_btn col-3" id="revert_btn" value="' . Crypt::encryptString($request_id) . '"><i class="fas fa-undo"></i></button>';
            } else if ($form_data[0]->state_approval == 3) {
                $status_icon[0] = '<i class="fa-solid fa-check"></i>';
            }
            $progress_div = '<div class="d-flex col-12 border flex-column justify-content-center main_progress_div">
                    <div class="d-flex progres_div gap-2">
                        <p class="col-3 ">Level</p>
                        <p class="col-3 ">Status</p>
                        <p class="col-3 ">Reason</p>
                    </div>
                    <div class="d-flex progres_div_1 align-items-center  gap-2">
                        <p class="col-3 ">State</p>
                        <div class="d-flex progres_div_2 col-3">
                            <p class="col-12 ">' . $status_icon[0] . '</p>
                        </div>
                        <p class="col-3 ">' . $form_data[0]->state_remarks . '</p>' . $revert_btn[0] . '
                    </div>
                </div>';
        } else {
            $progress_div = '<p>No Progress Found </p>';
        }
        return $progress_div;
    }
    // Revert Form To Block
    public static function revertFormMethod($table, $request_id)
    {
        try {
            DB::table($table)
                ->where('form_request_id', Crypt::decryptString($request_id))
                ->where('state_approval', 2)
                ->update([
                    'district_approval' => 2,
                    'district_remarks' => (DB::select('select state_remarks from ' . $table . ' where form_request_id = "' . Crypt::decryptString($request_id) . '" ')[0]->state_remarks)
                ]);
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
}
