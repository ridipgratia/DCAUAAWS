<?php

namespace App\MyMethod;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\Table\Table;

class DelayEmpForm
{
    // To Check Is Date Exists Or Not 
    public static function checkIsDateAvai($date, $table)
    {
        $check_date = DB::table($table)->where('submited_by', Auth::user()->login_id)->where('date_of_submit', $date)->get();
        if (count($check_date) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // To Get Data Date Wise 
    public static function getFromdata($date, $table)
    {
        $form_data = DB::table($table)->where('submited_by', Auth::user()->login_id)->where('date_of_submit', $date)->get();
        return $form_data;
    }
    // get Approval data
    public static function getApproaveData($table)
    {
        $form_list = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('approval_status', 3)
            ->select(
                'id',
                'request_id',
                'date_of_submit'
            )->get();
        return $form_list;
    }
    // Check Is FTO No Exists 
    public static function checkIsFTO($table, $form_id)
    {
        $FTO_lists = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('form_id', $form_id)
            ->select('id')->get();
        if (count($FTO_lists) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // Check Form ID Avaible Or Not 
    public static function checkFormIDAvai($table, $form_id)
    {
        $check_form_id = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('id', $form_id)
            ->select('id')->get();
        if (count($check_form_id) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // Check Approval Status Of Form ID
    public static function checkApprovalStatus($table, $form_id)
    {
        $check_approval = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('id', $form_id)
            ->where('approval_status', 3)
            ->select('id')->get();
        if (count($check_approval) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // Get FTO Number 
    public static function getFTOData($table, $form_id)
    {
        $get_FTO_data = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('form_id', $form_id)
            ->get();
        return $get_FTO_data;
    }
    //  Get District Name
    public static function getDistrictName($district_code)
    {
        $district_name = DB::table('districts')->where('district_code', $district_code)->select('district_name')->get();
        return $district_name[0]->district_name;
    }
    // Get Block Name
    public static function getBlockName($block_id)
    {
        $block_name = DB::table('blocks')->where('block_id', $block_id)->select('block_name')->get();
        return $block_name[0]->block_name;
    }
    // get GP Names
    public static function getGPName($block_id)
    {
        $gp_names = DB::table('gram_panchyats')->where('block_id', $block_id)->select('gram_panchyat_id', 'gram_panchyat_name')->get();
        return $gp_names;
    }
    // Search By Dates And Gp Name 
    public static function searchDatesGp($form_date, $to_date, $gp_name, $table)
    {
        $status = null;
        $message = null;
        if ($form_date === null && $to_date === null && $gp_name === null) {
            $status = 200;
            $result = DB::table($table)->where('submited_by', Auth::user()->login_id)->get();
            $message = array($result);
        } else {
            if (($form_date === null && $to_date !== null) || ($form_date !== null && $to_date === null)) {
                $status = 400;
                $message = "Select Both Dates ";
            } else {
                if ($form_date !== null && $gp_name !== null) {
                    if ($form_date <= $to_date) {
                        $form_to_date = DelayEmpForm::getPeriodDates($form_date, $to_date);
                        $form_date_his = array();
                        foreach ($form_to_date as $dates) {
                            if (DelayEmpForm::checkIsDateAvai($dates, $table)) {
                                $form_data = DelayEmpForm::getFilterData($dates, $table, $gp_name);
                                array_push($form_date_his, $form_data);
                            }
                        }
                        $status = 200;
                        $message = $form_date_his;
                    } else {
                        $status = 400;
                        $message = "Select A Valid dates";
                    }
                } else {
                    if ($gp_name !== null) {
                        $result = DB::table($table)->where('submited_by', Auth::user()->login_id)->where('gp_id', $gp_name)->get();
                        $status = 200;
                        $message = array($result);
                    } else {
                        if ($form_date !== null) {
                            if ($form_date <= $to_date) {
                                $form_to_date = DelayEmpForm::getPeriodDates($form_date, $to_date);
                                $form_date_his = array();
                                foreach ($form_to_date as $dates) {
                                    if (DelayEmpForm::checkIsDateAvai($dates, $table)) {
                                        $form_data = DelayEmpForm::getFromdata($dates, $table);
                                        array_push($form_date_his, $form_data);
                                    }
                                }
                                $status = 200;
                                $message = $form_date_his;
                            } else {
                                $status = 400;
                                $message = "Select A Valid Dates ";
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
    // get Filter Data By Dates And Gp
    public static function getFilterData($date, $table, $gp_code)
    {
        $result = DB::table($table)
            ->where('submited_by', Auth::user()->login_id)
            ->where('date_of_submit', $date)
            ->where('gp_id', $gp_code)
            ->get();
        return $result;
    }

    // Check Form Pending
    public static function checkFormPending($table)
    {
        $form_list = DB::table($table)
            ->where('district_id', Auth::user()->district)
            ->where('block_id', Auth::user()->block)
            ->where('approval_status', '<>', 3)
            ->count();
        if ($form_list == 0) {
            return true;
        } else {
            return false;
        }
    }
    public static function chekcFormStatus($table, $register_id)
    {
        $state_icon = ['<i class="fa-solid fa-hourglass-half"></i>', '<i class="fa-solid fa-hourglass-half"></i>'];
        $edit_btn = ['', ''];
        $progress_div = "";
        $form_data = DB::table($table)
            ->where('form_request_id', $register_id)
            ->get();

        if (count($form_data) != 0) {
            if ($form_data[0]->district_approval == 1 || $form_data[0]->district_approval == 2 || $form_data[0]->district_approval == 3) {
                if ($form_data[0]->district_approval == 2) {
                    $state_icon[0] = '<i class="fa-solid fa-xmark"></i>';
                } else if ($form_data[0]->district_approval == 3) {
                    $state_icon[0] = '<i class="fa-solid fa-check"></i>';
                }
                if ($form_data[0]->district_approval == 2) {
                    $edit_btn[0] = '<button class="form_edit_btn col-3 m-2" id="edit_form_btn" value="' . Crypt::encryptString($register_id) . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                }
            }
            if ($form_data[0]->state_approval == 1 || $form_data[0]->state_approval == 2 || $form_data[0]->state_approval == 3) {
                if ($form_data[0]->state_approval == 2) {
                    $state_icon[1] = '<i class="fa-solid fa-xmark"></i>';
                } else if ($form_data[0]->state_approval == 3) {
                    $state_icon[1] = '<i class="fa-solid fa-check"></i>';
                }
                if ($form_data[0]->state_approval == 2 && $form_data[0]->district_approval == 2) {
                    $edit_btn[0] = '<button class="form_edit_btn col-3 m-2" id="edit_form_btn" value="' . Crypt::encryptString($register_id) . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                }
            }
            $progress_div = '<div class="d-flex col-12 border flex-column justify-content-center main_progress_div">
            <div class="d-flex progres_div gap-2">
                <p class="col-3 ">Level</p>
                <p class="col-3 ">Status</p>
                <p class="col-3 ">Reason</p>
            </div>
            <div class="d-flex progres_div_1 align-items-center  gap-2">
                <p class="col-3 ">District</p>
                <div class="d-flex progres_div_2 col-3">
                    <p class="col-12 ">' . $state_icon[0] . '</p>
                </div>
                <p class="col-3 ">' . $form_data[0]->district_remarks . '</p>' . $edit_btn[0] . '
            </div>
            <div class="d-flex progres_div_1  gap-2">
                <p class="col-3 ">State</p>
                <div class="d-flex progres_div_2 col-3">
                    <p class="col-12 ">' . $state_icon[1] . '</p>
                </div>
                <p class="col-3 ">' . $form_data[0]->state_remarks . '</p>' . $edit_btn[1] . '
            </div>
        </div>';
        }
        return $progress_div;
    }
    // Check Form Reject
    public static function checkFormReject($table, $request_id)
    {
        try {
            $form = DB::table($table)
                ->where('form_request_id', Crypt::decryptString($request_id))
                ->where('district_approval', 2)
                ->orWhere('state_approval', 2)
                ->get();
            if (count($form) == 0) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $err) {
            return false;
        }
    }
    // Get All Form Data 
    public static  function getAllFormData($table, $request_id)
    {
        try {
            $form_data = DB::table($table)
                ->where('submited_by', Auth::user()->login_id)
                ->where('request_id', Crypt::decryptString($request_id))
                ->get();
            return $form_data;
        } catch (Exception $err) {
            return NULL;
        }
    }
    // Check Form Exists 
    public static function checkFormExists($table, $request_id)
    {
        try {
            return DB::table($table)
                ->select('id')
                ->where('request_id', Crypt::decryptString($request_id))
                ->count();
        } catch (Exception $err) {
            return false;
        }
    }
    // Get Submited By ID
    public static function getSubmitedBy($table, $request_id)
    {
        try {
            return DB::table($table)
                ->where('request_id', Crypt::decryptString($request_id))
                ->select('submited_by')
                ->get();
        } catch (Exception $err) {
            return NULL;
        }
    }
    // Update Edit Form Data In Database
    public static function updateEditFormDatabase($table, $update_fields, $request_id)
    {
        try {
            DB::table($table)
                ->where('request_id', Crypt::decryptString($request_id))
                ->update(
                    $update_fields
                );
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    public static function editDataUpdate($table, $request_id, $update_filed_data)
    {
        try {
            DB::table($table)
                ->where('form_request_id', $request_id)
                ->update(
                    $update_filed_data
                );
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    // Revert Update Data From Database
    public static function revertEditData($table, $request_id)
    {
        $update_fields_data = [
            'district_approval' => 2,
            'state_approval' => 2
        ];
        return DelayEmpForm::editDataUpdate($table, $request_id, $update_fields_data);
    }
    // Update Form data
    public static function updateAllFormData($table, $sub_table, $request, $update_fields, $check_fileds)
    {
        $status = 400;
        $message = null;
        if (DelayEmpForm::checkFormExists($table, $request->request_id)) {
            if (DelayEmpForm::checkFormReject($sub_table, $request->request_id)) {
                $error_message = [
                    'required' => '* :attribute Is Required Field',
                    'mimes' => '* :attribute Is Only Accept PDF',
                    'date' => '* :attribute Is Date Format',
                    'integer' => '* :attribute Is Number Format',
                    'max' => '* File Size Only 3mb',
                ];
                $validator = Validator::make(
                    $request->all(),
                    $check_fileds,
                    $error_message
                );
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $errors) {
                        $message .= $errors . '<br>';
                    }
                } else {
                    $submited_by = DelayEmpForm::getSubmitedBy($table, $request->request_id);
                    if ($submited_by) {
                        $update_fields_data = [
                            'district_approval' => 1,
                            'state_approval' => 1
                        ];
                        $check = DelayEmpForm::editDataUpdate($sub_table, Crypt::decryptString($request->request_id), $update_fields_data);
                        if ($check) {
                            $bank_statement_url = $request->file('bank_statement_url')->store('public/images/' . $submited_by[0]->submited_by . '/unemploye_allowance');
                            $update_fields['bank_statement_url'] = $bank_statement_url;
                            $check_update = DelayEmpForm::updateEditFormDatabase($table, $update_fields, $request->request_id);
                            if ($check_update) {
                                $message = "Successfully Update Form Data";
                                $status = 200;
                            } else {
                                $check = DelayEmpForm::revertEditData($sub_table, Crypt::decryptString($request->request_id));
                                if ($check) {
                                    $message = "Server Error Please Try Later In Update Data!";
                                } else {
                                    $message = "Revert Failed Please Contact Developers";
                                }
                            }
                        } else {
                            $message = "Server Error Please Try Later";
                        }
                    } else {
                        $message = "Server Error Please Try Later 1!";
                    }
                }
            } else {
                $message = "Form Is Not Editable !";
            }
        } else {
            $message = "Request Form Not Found !";
        }
        return [$status, $message];
    }
    // Delete Form table
    public static function deleteFormTable($table, $request_id, $column_name)
    {
        try {
            DB::table($table)
                ->where($column_name, Crypt::decryptString($request_id))
                ->delete();
            return true;
        } catch (Exception $err) {
            return false;
        }
    }
    // Delete form 
    public static function deleteForm($table, $sub_table, $request_id)
    {
        $status = 400;
        $message = null;
        if (DelayEmpForm::checkFormExists($table, $request_id)) {
            if (DelayEmpForm::checkFormReject($sub_table, $request_id)) {
                $tables = [
                    $table => 'request_id',
                    $sub_table => 'form_request_id'
                ];
                $check = false;
                foreach ($tables as $table_key => $table_value) {
                    if (DelayEmpForm::deleteFormTable($table_key, $request_id, $table_value)) {
                        $check = true;
                    } else {
                        $check = false;
                        break;
                    }
                }
                if ($check) {
                    $message = "Form Deleted Successfully";
                    $status = 200;
                } else {
                    $message = "Server Error While Deleting Form data";
                }
            } else {
                $message = "Form Can't Be Deleted !";
            }
        } else {
            $message = "Request Form Not Found";
        }
        return [$status, $message];
    }
}
