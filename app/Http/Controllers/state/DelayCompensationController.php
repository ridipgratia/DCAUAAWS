<?php

namespace App\Http\Controllers\state;

use App\Http\Controllers\Controller;
use App\MyMethod\MailSender;
use App\MyMethod\StateMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsUnprocessable;

class DelayCompensationController extends Controller
{
    public function all_list()
    {
        $districts = StateMethod::getDistricts();
        return view('state.delay_compensation', [
            'districts' => $districts,
        ]);
    }
    // Get Blocks By District Code
    public function get_blocks(Request $request)
    {
        if ($request->ajax()) {
            $district_code = $_GET['district_code'];
            $blocks = StateMethod::getBlocks($district_code);
            $content = "<option disabled selected>Select</option>";
            foreach ($blocks as $block) {
                $content .= '<option value="' . $block->block_id . '">' . $block->block_name . '</option>';
            }
            return response()->json(['status' => 200, 'message' => $content]);
        }
    }
    // Get Gps By Block Id
    public function get_gps(Request $request)
    {
        if ($request->ajax()) {
            $block_id = $_GET['district_code'];
            $gps = StateMethod::getGP($block_id);
            $content = "<option disabled selected>Select</option>";
            foreach ($gps as $gp) {
                $content .= '<option value="' . $gp->gram_panchyat_id . '">' . $gp->gram_panchyat_name . '</option>';
            }
            return response()->json(['status' => 200, 'message' => $content]);
        }
    }
    public function get_delay_com(Request $request)
    {
        if ($request->ajax()) {
            $form_lists = StateMethod::getFormLists('add_dc');
            return response()->json(['status' => 200, 'message' => $form_lists]);
        }
    }
    public function view_form_by_id(Request $request)
    {
        if ($request->ajax()) {
            $delay_form_id = $_GET['delay_form_id'];
            if (isset($delay_form_id)) {
                $delay_form_data = DB::table('add_dc')
                    ->where('id', $delay_form_id)
                    ->get();
                if (count($delay_form_data) == 0) {
                    $content = "<p>No data Found</p>";
                } else {
                    $img_url = Storage::url($delay_form_data[0]->bank_statement_url);
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
            <button id="show_form_document" class="btn btn-primary" value="' . $img_url . '"><i
            class="fa-solid fa-file"></i></button>
        <button id="approved_state_btn" class="btn btn-primary btn-success"
            value="' . $delay_form_data[0]->id . '">Accept</button>
        <button id="reject_state_btn" class="btn btn-primary btn-danger"
            value="' . $delay_form_data[0]->id . '">Reject</button><div class="d-flex flex-column col-12 mt-4 district_reason_div">
            <p class="col-md-6 col-8">Reason for rejection</p>
            <textarea class="form-control col-md-4 mb-2" id="form_reason" name="editor" rows="3" style="width:70%; resize:none;"></textarea>
        <button class="col-md-4 col-8 btn btn-success mb-2" id="form_reject_btn" value="' . $delay_form_data[0]->id . '">Submit</button>
        <button class="col-md-4 col-8 btn btn-warning" id="form_reason_cancel" value="' . $delay_form_data[0]->id . '">Cancel</button>
        </div>';
                }
            } else {
                $content = "<p>No Data</p>";
            }
            return response()->json(['status' => 200, 'message' => $content]);
        }
    }
    public function search_query(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->from_date_form;
            $to_date = $request->to_date_form;
            $block_name = $request->block_name;
            $gp_name = $request->gp_name;
            $district_name = $request->district_name;
            $result = StateMethod::searchByDisBloGpDates($form_date, $to_date, $district_name, $block_name, $gp_name, 'add_dc');
            return response()->json(['status' => $result[0], 'message' => $result[1]]);
        }
    }
    public function pending_list(Request $request)
    {
        $districts = StateMethod::getDistricts();
        return view('state.delay_pending', [
            'districts' => $districts,
        ]);
    }
    public function pending_list_data(Request $request)
    {
        if ($request->ajax()) {
            $status = null;
            $message = null;
            $pendingData = StateMethod::getPendingFormList('add_dc', 'delay_form_status');
            if ($pendingData) {
                $message = $pendingData;
                $status = 200;
            } else {
                $status = 400;
                $message = "No Data Found !";
            }
            return response()->json(['status' => 200, 'message' => $pendingData]);
        }
    }
    public function pending_filter_data(Request $request)
    {
        if ($request->ajax()) {
            $form_date = $request->from_date_form;
            $to_date = $request->to_date_form;
            $block_name = $request->block_name;
            $gp_name = $request->gp_name;
            $district_name = $request->district_name;
            $stauts = 400;
            $message = null;
            try {
                $result = StateMethod::searchByDisBloGpDatesPending($form_date, $to_date, $district_name, $block_name, $gp_name, 'add_dc', 'delay_form_status');
                $message = $result[1];
                $stauts = 200;
            } catch (Exception $err) {
                $message = "Error Execute In database ";
            }
            return response()->json(['status' => $stauts, 'message' => $message]);
        }
    }
    public function approved_pending_form(Request $request)
    {
        if ($request->ajax()) {
            $status = 400;
            $message = null;
            if (isset($_GET['form_id']) && isset($_GET['approval_index'])) {
                $form_id = $_GET['form_id'];
                $approval_index = $_GET['approval_index'];
                $approval_reason = $_GET['approval_reason'];
                $check_reason = true;
                if ($approval_index == 2) {
                    if ($approval_reason === "") {
                        $check_reason = false;
                    }
                } else {
                    $approval_reason = NULL;
                }
                if ($check_reason) {
                    $request_id = StateMethod::getRequestID('add_dc', $form_id);
                    if (count($request_id) == 0) {
                        $message = "Form Not Found !";
                    } else {
                        $check = StateMethod::approvalMethod('add_dc', 'delay_form_status', $request_id[0]->request_id, $approval_index, $approval_reason);
                        if ($check) {
                            $request_form_data = StateMethod::getRequestFormData('add_dc', $request_id[0]->request_id);
                            if ($request_form_data) {
                                $subject = "";
                                $body = "";
                                if ($approval_index == 3) {
                                    $body = "Your Form Request ID " . $request_id[0]->request_id . " has been approved by state .";
                                    $subject = "Request Form Successfully Approved By State";
                                } else {
                                    $subject = "Request Form Rejected By State";
                                    $body = "Your Form Request ID " . $request_id[0]->request_id . " Has Been Rejected By State as " . $approval_reason;
                                }
                                $notification = [
                                    'district_id' => $request_form_data[0]->district_id,
                                    'block_id' => $request_form_data[0]->block_id,
                                    'gp_id' => $request_form_data[0]->gp_id,
                                    'subject' => $subject,
                                    'description' => $body,
                                    'today' => date('Y-m-d')
                                ];
                                $check = StateMethod::approvalNotification($notification);
                                if ($check) {
                                    // $check = MailSender::sendMailer();
                                    $notify_email = StateMethod::getNotifyEmail($request_form_data[0]->district_id, $request_form_data[0]->block_id);
                                    if ($notify_email[1]) {
                                        $email_data = [
                                            'subject' => $subject,
                                            'body' => $body
                                        ];
                                        $check_email_msg = [
                                            'District' => '',
                                            'Block' => ''
                                        ];
                                        $check__msg = [
                                            'District' => '',
                                            'Block' => ''
                                        ];
                                        foreach ($notify_email[0] as $email_key => $email_value) {
                                            if ($email_value) {
                                                $check = MailSender::sendMailer($email_data, $email_value, 'mail_blades.notification');
                                                if (!$check) {
                                                    $check_email_msg[$email_key] = $email_key;
                                                } else {
                                                    $check__msg[$email_key] = $email_key;
                                                }
                                            } else {
                                                $check_email_msg[$email_key] = $email_key;
                                            }
                                        }
                                        if ($check__msg['District'] != '' || $check__msg['Block'] != '') {
                                            $message = "Email Send To " . $check__msg['District'] . ' ' . $check__msg['Block'] . ' . <br>';
                                        }
                                        if ($check_email_msg['District'] != '' || $check_email_msg['Block'] != '') {
                                            $message = $message . " And Email Not Send To " . $check_email_msg['District'] . ' ' . $check_email_msg['Block'] . ' .';
                                        }
                                        $message .= " <b>Approval Submited <b>";
                                        $status = 200;
                                        // $message = $data;
                                    } else {
                                        $message = "Aproval Submited But Email Not Send ";
                                    }
                                    // if ($check) {
                                    //     $status = 200;
                                    //     $message = $notify_email;
                                    // } else {
                                    //     $message = "Approval Submited But Mail Not Send !";
                                    // }
                                } else {
                                    $message = "Server Error ! Notification Not Send But Form Approved";
                                }
                            } else {
                                $message = "Server Error !  But Form Approved";
                            }
                        } else {
                            $message = "Server Error Try Later !";
                        }
                    }
                } else {
                    $message = "Please Fill A Reaon For Rejection";
                }
            } else {
                $status = 400;
                $message = "Try Later ";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
