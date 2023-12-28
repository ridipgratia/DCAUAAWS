<?php

namespace App\Http\Controllers;

use App\MyMethod\DelayEmpForm;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UnemployeAllowanceFromListController extends Controller
{
    public function create()
    {
        $district_name = DelayEmpForm::getDistrictName(Auth::user()->district);
        $block_name = DelayEmpForm::getBlockName(Auth::user()->block);
        $gp_names = DelayEmpForm::getGPName(Auth::user()->block);
        return view('unemp_allowance_form_list', [
            'district_name' => $district_name,
            'block_name' => $block_name,
            'gp_names' => $gp_names
        ]);
    }
    public function form_list(Request $request)
    {
        // Get All Delay Form List To Datatable 

        if ($request->ajax()) {
            $emp_code = Auth::user()->login_id;
            $unemp_allow_form_list_data = DB::table('add_unemp_allowance')->where('submited_by', $emp_code)->select('id', 'card_number', 'work_demand', 'recover_amount', 'date_of_submit', 'request_id', 'approval_status')
                ->orderBy('date_of_submit', 'asc')
                ->get();
            return response()->json(['message' => $unemp_allow_form_list_data]);
        }
    }
    // Display Submited Form Data Using ID And Employe Code 
    // Render From controller To Blade File 
    public function form_list_data(Request $request)
    {
        if ($request->ajax()) {
            $delay_form_id = $_GET['unemp_allow_form_id'];
            if (isset($delay_form_id)) {
                $emp_code = Auth::user()->login_id;
                $delay_form_data = DB::table('add_unemp_allowance')->where('submited_by', $emp_code)->where('id', $delay_form_id)->get();
                if (count($delay_form_data) == 0) {
                    return "<p>No data Found</p>";
                } else {
                    $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
                    $request_id = $delay_form_data[0]->request_id;
                    $approval_form_status = DelayEmpForm::chekcFormStatus('unemp_form_status', $request_id);
                    $content = '<p class="delay_para_head para_head">Work Code Number</p>
            <p class="delay_para para_1">' . $delay_form_data[0]->card_number . '</p>
            <p class="delay_para_head para_head">MR Number</p>
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
            <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '">View Upload Document</button>' . $approval_form_status;
                    return $content;
                }
            } else {
                return "<p>No data</p>";
            }
        }
    }

    // Fetch Data Dates Wise And Send To Blade File 
    public function search_form_date(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->from_date_form;
            $to_date = $request->to_date_form;
            $gp_name = $request->gp_name;
            $result = DelayEmpForm::searchDatesGp($form_date, $to_date, $gp_name, 'add_unemp_allowance');
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    public function editFormMethod(Request $request)
    {
        if ($request->ajax()) {
            $request_id = $_GET['request_id'];
            if (isset($request_id)) {
                if (DelayEmpForm::checkFormReject('unemp_form_status', $request_id)) {
                    $delay_form_data = DelayEmpForm::getAllFormData('add_unemp_allowance', $request_id);
                    if ($delay_form_data == NULL) {
                        return "<p>No data Found</p>";
                    } else {
                        $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
                        // $request_id = $delay_form_data[0]->request_id;
                        // $approval_form_status = DelayEmpForm::chekcFormStatus('delay_form_status', $request_id);
                        $content = '<form id="submit_edit_form" class="flex_div delay_show_div_1 from_list_show_div_1" style="width:100%;">
                        <input type="hidden" value="' . csrf_token() . '" name="_token">
                    <p class="delay_para_head para_head para_head_edit">Card Number</p>
                        <input type="text" name="card_number" class="delay_para para_1" value="' . $delay_form_data[0]->card_number . '" >
                        <p class="delay_para_head para_head para_head_edit">Work Demand</p>
                        <input type="text" name="work_demand" class="delay_para para_1" value="' . $delay_form_data[0]->work_demand . '">
                        <p class="delay_para_head para_head para_head_edit">Total Day Unemployed</p>
                        <input type="text" name="total_day_unemploye" class="delay_para para_1" value="' . $delay_form_data[0]->total_day_unemple . '">
                        <p class="delay_para_head para_head para_head_edit">Person Responsible For Delay</p>
                        <input type="text" name="person_delay" class="delay_para para_1" value="' . $delay_form_data[0]->person_delay . '">
                        <p class="delay_para_head para_head para_head_edit">Designation Amount</p>
                        <input type="text" name="designation_delay" class="delay_para para_1" value="' . $delay_form_data[0]->designation_delay . '">
                        <p class="delay_para_head para_head para_head_edit">Amount Recovered</p>
                        <input type="number" name="recover_amount" class="delay_para para_1" value="' . $delay_form_data[0]->recover_amount . '">
                        <p class="delay_para_head para_head para_head_edit">Date Recovered Amount</p>
                        <input type="date" name="date_recover_amount" class="delay_para para_1" value="' . $delay_form_data[0]->date_recover_amount . '">
                        <p class="delay_para_head para_head para_head_edit">Date Deposite Bank </p>
                        <input type="date" name="date_deposite_bank" class="delay_para para_1" value="' . $delay_form_data[0]->date_deposite_bank . '">
                        <p class="delay_para_head para_head para_head_edit">Select Your Document </p>
                        <input type="file" name="bank_statement_url" class="delay_para para_1" accept="application/pdf">
                        <div>
                            <button type="button" id="show_form_document" class="btn btn-primary" value="' . $img_url . '">View Document</button>
                        </div>
                        <div class="mt-3 gap-2">
                            <button id="delete_form_btn" class="btn btn-danger" value="' . $request_id . '">Delete Form</button>
                            <button id="update_edit_form" class="btn btn-success" value="' . $request_id . '">Submit Form</button>
                        </div>
                        </form>';
                        return $content;
                    }
                } else {
                    return "<p>No Data 2</p>";
                }
            } else {
                return "<p>No data 1</p>";
            }
        }
    }
    // Submit Edit Form Data 
    public function updateEditForm(Request $request)
    {
        if ($request->ajax()) {
            $update_fields = [
                "card_number" => $request->card_number,
                "work_demand" => $request->work_demand,
                "total_day_unemple" => $request->total_day_unemploye,
                "person_delay" => $request->person_delay,
                "designation_delay" => $request->designation_delay,
                "recover_amount" => $request->recover_amount,
                "date_recover_amount" => $request->date_recover_amount,
                "date_deposite_bank" => $request->date_deposite_bank,
                "bank_statement_url" => $request->bank_statement_url,
                "form_update_date" => date('Y-m-d')
            ];
            $check_fields = [
                'card_number' => 'required',
                'work_demand' => 'required',
                'total_day_unemploye' => 'required',
                'person_delay' => 'required',
                'designation_delay' => 'required|',
                'recover_amount' => 'required|integer',
                'date_recover_amount' => 'required|date',
                'date_deposite_bank' => 'required|date',
                'bank_statement_url' => 'required|max:3072|mimes:pdf',
            ];
            $response = DelayEmpForm::updateAllFormData('add_unemp_allowance', 'unemp_form_status', $request, $update_fields, $check_fields);
            return response()->json(['status' => $response[0], 'message' => $response[1]]);
        }
    }
    // Delete form
    public static function deleteFormMethod(Request $request)
    {
        if ($request->ajax()) {
            $request_id = $_GET['request_id'];
            $response = DelayEmpForm::deleteForm('add_unemp_allowance', 'unemp_form_status', $request_id);
            return response()->json(['status' => $response[0], 'message' => $response[1]]);
        }
    }
}
