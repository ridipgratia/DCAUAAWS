<?php

namespace App\Http\Controllers\district;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MyMethod\DistrictMethod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UnempAllowance extends Controller
{
    public function index()
    {
        $district_name = DistrictMethod::getDistrictName();
        $block_names = DistrictMethod::getBlocksName();
        return view('district.unemp_allowance_list', [
            'district_name' => $district_name,
            'block_names' => $block_names,
        ]);
    }
    public function form_list(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['card_number', 'work_demand', 'recover_amount'];
            $lists = DistrictMethod::GetFormList('add_unemp_allowance', 'unemp_form_status', $columns);
            return response()->json(['status' => 200, 'message' => $lists]);
        }
    }
    public function form_data(Request $request)
    {
        if ($request->ajax()) {
            $delay_form_id = $_GET['delay_form_id'];
            if (isset($delay_form_id)) {
                $content = DistrictMethod::unempViewFormData('add_unemp_allowance', $delay_form_id);
            } else {
                $content = "<p>No data</p>";
            }
            return response()->json(['status' => 200, 'message' => $content]);
        }
    }
    public function search_data(Request $request)
    {
        if ($request->ajax()) {
            $from_date_form = $request->from_date_form;
            $to_date_form = $request->to_date_form;
            $form_data = DistrictMethod::searchByDate('add_unemp_allowance', $from_date_form, $to_date_form);
            return response()->json(['status' => $form_data[0], 'message' => $form_data[1]]);
        }
    }
    public function search_block_gp_dates(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->from_date_form;
            $to_date = $request->to_date_form;
            $block_name = $request->block_name;
            $gp_name = $request->gp_name;
            $result = DistrictMethod::searchByBlockGpDates($form_date, $to_date, $block_name, $gp_name, 'add_unemp_allowance', 'unemp_form_status', 1);
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    // View Unemp Allowance Blade File
    public function approval_list()
    {
        $district_name = DistrictMethod::getDistrictName();
        $block_names = DistrictMethod::getBlocksName();
        return view('district.unemp_approval', [
            'district_name' => $district_name,
            'block_names' => $block_names,
        ]);
    }
    public function load_approval_list()
    {
        $data = DistrictMethod::loadApprovalData('add_unemp_allowance', 'unemp_form_status');
        return response()->json(['status' => 200, 'message' => $data]);
    }
    public function view_approval_form(Request $request)
    {
        if ($request->ajax()) {
            if (isset($_GET['delay_form_id'])) {
                $delay_form_id = $_GET['delay_form_id'];
                //     $main_content = DistrictMethod::viewFormData('add_dc', $delay_form_id);
                //     $request_id = DistrictMethod::getRequestID('add_dc', $delay_form_id);
                //     $progress_div = null;
                //     if (count($request_id) != 0) {
                //         $progress_div = null;
                //     }
                //     $progress_div = '<div class="d-flex col-12 border flex-column justify-content-center main_progress_div">
                //     <div class="d-flex progres_div gap-2">
                //         <p class="col-3 ">Level</p>
                //         <p class="col-3 ">Status</p>
                //         <p class="col-3 ">Reason</p>
                //     </div>
                //     <div class="d-flex progres_div_1 align-items-center  gap-2">
                //         <p class="col-3 ">District</p>
                //         <div class="d-flex progres_div_2 col-3">
                //             <p class="col-12 "><i class="fa-solid fa-xmark"></i></p>
                //         </div>
                //         <p class="col-3 ">just testing reason.</p><button class="form_edit_btn col-3"><i class="fas fa-undo"></i></button>
                //     </div>
                // </div>';
                //     $content = $main_content . $progress_div;
                // } else {
                //     $content = "<p>No Data</p>";
                // }
                $main_content = '';
                $form_data = DistrictMethod::viewApprovedData('add_unemp_allowance', $delay_form_id);
                if (count($form_data) != 0) {
                    $content = DistrictMethod::viewUnempApprovedData($form_data);
                    $progress_div = DistrictMethod::checkApprovalStatus('unemp_form_status', $form_data[0]->request_id);
                    $main_content = $content . $progress_div;
                } else {
                    $main_content = '<p>No Data Found !</p>';
                }
                return response()->json(['status' => 200, 'message' => $main_content]);
            }
        }
    }
    public function approval_form_data(Request $request)
    {
        if ($request->ajax()) {
            $status = null;
            $message = null;
            if (isset($_GET['form_id']) && isset($_GET['approval_index'])) {
                $form_id = $_GET['form_id'];
                $approval_index = $_GET['approval_index'];
                $reason = $_GET['aproval_reason'];
                $check_reason = true;
                if ($approval_index == 2) {
                    if ($reason === "") {
                        $check_reason = false;
                    }
                } else {
                    $reason = NULL;
                }
                if ($check_reason) {
                    $request_id = DistrictMethod::getRequestID('add_unemp_allowance', $form_id);
                    if ($request_id) {
                        if (DistrictMethod::approvalMethod('add_unemp_allowance', 'unemp_form_status', $request_id[0]->request_id, $approval_index, $reason)) {
                            $status = 200;
                            $message = "Approval Submited";
                        } else {
                            $status = 400;
                            $message = "Try Later , Problem At Database !";
                        }
                    } else {
                        $status = 400;
                        $message = "Form Not Found !";
                    }
                } else {
                    $status = 400;
                    $message = "Please Fill A Reason To Reject Application";
                }
            } else {
                $status = 400;
                $message = "Try Later !";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
    public function search_block_gp_dates_pending(Request $request)
    {
        $form_date = $request->from_date_form;
        $to_date = $request->to_date_form;
        $block_name = $request->block_name;
        $gp_name = $request->gp_name;
        $result = DistrictMethod::searchByBlockGpDates($form_date, $to_date, $block_name, $gp_name, 'add_unemp_allowance', 'unemp_form_status', 3);
        return response()->json(['status' => $result[0], 'message' => $result[1]]);
    }
    public function revert_form(Request $request)
    {
        if ($request->ajax()) {
            $status = 400;
            $message = null;
            $request_id = $_GET['request_id'];
            $check = DistrictMethod::revertFormMethod('unemp_form_status', $request_id);
            if ($check) {
                $message = "Form Reverted Successfully";
                $status = 200;
            } else {
                $message = "Server Error Try Again !";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
