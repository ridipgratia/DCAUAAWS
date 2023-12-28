<?php

namespace App\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\MyMethod\DelayEmpForm;
use App\MyMethod\DistrictMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DelayConpensationFormListController extends Controller
{
    public function create()
    {
        // Load Delay Form List
        $district_name = DelayEmpForm::getDistrictName(Auth::user()->district);
        $block_name = DelayEmpForm::getBlockName(Auth::user()->block);
        $gp_names = DelayEmpForm::getGPName(Auth::user()->block);
        return view('delay_compensation_form_list', [
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
            $delay_form_list_data = DB::table('add_dc')->where('submited_by', $emp_code)->select('id', 'code_number', 'mr_number', 'recover_amount', 'date_of_submit', 'request_id', 'approval_status')
                ->orderBy('date_of_submit', 'asc')
                ->get();
            return response()->json(['message' => $delay_form_list_data]);
        }
    }

    // Display Submited Form Data Using ID And Employe Code 
    // Render From controller To Blade File 
    public function form_list_data(Request $request)
    {
        if ($request->ajax()) {
            $delay_form_id = $_GET['delay_form_id'];
            if (isset($delay_form_id)) {
                $emp_code = Auth::user()->login_id;
                $delay_form_data = DB::table('add_dc')
                    ->where('submited_by', $emp_code)
                    ->where('id', $delay_form_id)->get();
                if (count($delay_form_data) == 0) {
                    return "<p>No data Found</p>";
                } else {
                    $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
                    $request_id = $delay_form_data[0]->request_id;
                    $approval_form_status = DelayEmpForm::chekcFormStatus('delay_form_status', $request_id);
                    $content = '<p class="delay_para_head para_head">Work Code Number</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->code_number . '</p>
                        <p class="delay_para_head para_head">MR Number</p>
                        <p class="delay_para para_1">' . $delay_form_data[0]->mr_number . '</p>
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
        // <img src="' . $img_url . '" class="delay_image modal_image">
    }

    // Fetch Data Dates Wise And Send To Blade File 
    public function search_form_date(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->from_date_form;
            $to_date = $request->to_date_form;
            $gp_name = $request->gp_name;
            $result = DelayEmpForm::searchDatesGp($form_date, $to_date, $gp_name, 'add_dc');
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    //edit reverted form
    public static function editFormMethod(Request $request)
    {
        if ($request->ajax()) {
            $request_id = $_GET['request_id'];
            if (isset($request_id)) {
                if (DelayEmpForm::checkFormReject('delay_form_status', $request_id)) {
                    $delay_form_data = DelayEmpForm::getAllFormData('add_dc', $request_id);
                    if ($delay_form_data == NULL) {
                        return "<p>No data Found</p>";
                    } else {
                        $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
                        // $request_id = $delay_form_data[0]->request_id;
                        // $approval_form_status = DelayEmpForm::chekcFormStatus('delay_form_status', $request_id);
                        $content = '<form id="submit_edit_form" class="flex_div delay_show_div_1 from_list_show_div_1" style="width:100%;">
                        <input type="hidden" value="' . csrf_token() . '" name="_token">
                    <p class="delay_para_head para_head para_head_edit">Code Number</p>
                        <input type="text" name="code_number"  class="delay_para para_1" value="' . $delay_form_data[0]->code_number . '" >
                        <p class="delay_para_head para_head para_head_edit">MR Number</p>
                        <input type="text" name="mr_number"  class="delay_para para_1" value="' . $delay_form_data[0]->mr_number . '">
                        <p class="delay_para_head para_head para_head_edit">Person Responsible For Delay</p>
                        <input type="text" name="person_delay"  class="delay_para para_1" value="' . $delay_form_data[0]->person_delay . '">
                        <p class="delay_para_head para_head para_head_edit">Designation Responsible For Delay</p>
                        <input type="text" name="designation_delay"  class="delay_para para_1" value="' . $delay_form_data[0]->designation_delay . '">
                        <p class="delay_para_head para_head para_head_edit">Recovered Amount</p>
                        <input type="number" name="recover_amount"  class="delay_para para_1" value="' . $delay_form_data[0]->recover_amount . '">
                        <p class="delay_para_head para_head para_head_edit">Date Amount Recovered</p>
                        <input type="date" name="date_recover_amount"  class="delay_para para_1" value="' . $delay_form_data[0]->date_recover_amount . '">
                        <p class="delay_para_head para_head para_head_edit">Date Deposited To Bank</p>
                        <input type="date" name="date_deposite_bank"  class="delay_para para_1" value="' . $delay_form_data[0]->date_deposite_bank . '">
                        <p class="delay_para_head para_head para_head_edit">Select Your Document </p>
                        <input type="file" name="bank_statement_url" class="delay_para para_1" accept="application/pdf" >
                        <div>
                            <button  type="button" id="show_form_document" class="btn btn-primary" value="' . $img_url . '">View Document</button>
                        </div>
                        <div class="mt-3 gap-2">
                            <button type="button" id="delete_form_btn" class="btn btn-danger" value="' . $request_id . '">Delete Form</button>
                            <button type="button" id="update_edit_form" class="btn btn-success" value="' . $request_id . '">Submit Form</button>
                        </div>
                        </form>';
                        return $content;
                    }
                } else {
                    return "<p>No Data 2</p>";
                }
            } else {
                return "<p>No data </p>";
            }
        }
    }
    // Submit Edit Form Data 
    public function updateEditForm(Request $request)
    {
        if ($request->ajax()) {
            $update_fields = [
                "code_number" => $request->code_number,
                "mr_number" => $request->mr_number,
                "person_delay" => $request->person_delay,
                "designation_delay" => $request->designation_delay,
                "recover_amount" => $request->recover_amount,
                "date_recover_amount" => $request->date_recover_amount,
                "date_deposite_bank" => $request->date_deposite_bank,
                "bank_statement_url" => $request->bank_statement_url,
                "form_update_date" => date('Y-m-d')
            ];
            $check_fields = [
                'code_number' => 'required',
                'mr_number' => 'required',
                'person_delay' => 'required',
                'designation_delay' => 'required',
                'recover_amount' => 'required|integer',
                'date_recover_amount' => 'required|date',
                'date_deposite_bank' => 'required|date',
                'bank_statement_url' => 'required|max:3072|mimes:pdf',
            ];
            $response = DelayEmpForm::updateAllFormData('add_dc', 'delay_form_status', $request, $update_fields, $check_fields);
            return response()->json(['status' => $response[0], 'message' => $response[1]]);
        }
    }
    // Delete form
    public static function deleteFormMethod(Request $request)
    {
        if ($request->ajax()) {
            $request_id = $_GET['request_id'];
            $response = DelayEmpForm::deleteForm('add_dc', 'delay_form_status', $request_id);
            return response()->json(['status' => $response[0], 'message' => $response[1]]);
        }
    }
}
