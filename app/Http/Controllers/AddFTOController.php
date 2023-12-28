<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MyMethod\DelayEmpForm;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AddFTOController extends Controller
{
    // View Delay Add FTO  Page 
    public function viewDelayFTO()
    {
        return view('add_delay_FTO');
    }
    // View Unemployment FTO Page 

    public function viewUnempFTO()
    {
        return view('add_unemp_FTO');
    }
    // View Delay Form For Add FTO  

    public function view_form($table_1, $table_2)
    {
        $form_lists = DelayEmpForm::getApproaveData($table_1);
        foreach ($form_lists as $form_list) {
            if (DelayEmpForm::checkIsFTO($table_2, $form_list->id)) {
                $form_list->action_btn = "View FTO";
            } else {
                $form_list->action_btn = "Add FTO";
            }
            $form_list->id = Crypt::encryptString($form_list->id);
        }
        return $form_lists;
    }
    public function delay_view_form(Request $request)
    {
        if ($request->ajax()) {
            $form_lists = $this->view_form('add_dc', 'add_delay_fto');
            return response()->json(['status' => 200, 'message' => $form_lists]);
        }
    }
    public function unemp_view_form(Request $request)
    {
        if ($request->ajax()) {
            $form_lists = $this->view_form('add_unemp_allowance', 'add_unemp_fto');
            return response()->json(['status' => 200, 'message' => $form_lists]);
        }
    }
    public function add_fto($table_1, $table_2)
    {
        $form_id = $_GET['form_id'];
        $form_id = Crypt::decryptString($form_id);
        $status = null;
        $message = null;
        if (isset($form_id)) {
            if (DelayEmpForm::checkFormIDAvai($table_1, $form_id)) {
                if (DelayEmpForm::checkApprovalStatus($table_1, $form_id)) {
                    if (DelayEmpForm::checkIsFTO($table_2, $form_id)) {
                        $get_fto_data = DelayEmpForm::getFTOData($table_2, $form_id);
                        $status = 201;
                        $message = $get_fto_data[0]->FTO_no;
                    } else {
                        $status = 200;
                        $message = $form_id;
                    }
                } else {
                    $status = 400;
                    $message = "Form Not Approved !";
                }
            } else {
                $status = 400;
                $message = "Form Data Not Found !";
            }
        } else {
            $status = 400;
            $message = "Form ID Invisible !";
        }
        $data = [$status, $message];
        return $data;
    }
    public function delay_add_fto(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->add_fto('add_dc', 'add_delay_fto');
            return response()->json(['status' => $data[0], 'message' => $data[1]]);
        }
    }
    public function unemp_add_fto(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->add_fto('add_unemp_allowance', 'add_unemp_fto');
            return response()->json(['status' => $data[0], 'message' => $data[1]]);
        }
    }
    public function submit_fto($request, $table_1, $table_2)
    {
        $form_id = $request->form_id;
        $fto_no = $request->fto_no;
        $status = null;
        $message = null;
        if (isset($form_id)) {
            if ($fto_no === null) {
                $status = 400;
                $message = "Fill FTO No ";
            } else {
                if (DelayEmpForm::checkFormIDAvai($table_1, $form_id)) {
                    if (DelayEmpForm::checkApprovalStatus($table_1, $form_id)) {
                        if (DelayEmpForm::checkIsFTO($table_2, $form_id)) {
                            $status = 400;
                            $message = "FTO Number Already Added";
                        } else {
                            $check = false;
                            try {
                                $request_id = DB::table($table_1)
                                    ->where('submited_by', Auth::user()->login_id)
                                    ->where('id', $form_id)
                                    ->select(
                                        'request_id'
                                    )
                                    ->get();
                                $check = true;
                            } catch (Exception $err) {
                                $check = false;
                            }
                            if ($check) {
                                try {
                                    date_default_timezone_set('Asia/Kolkata');
                                    $add_date = date("Y-m-d");
                                    DB::table($table_2)->insert([
                                        'form_id' => $form_id,
                                        'FTO_no' => $fto_no,
                                        'add_date' => $add_date,
                                        'submited_by' => Auth::user()->login_id,
                                        "created_at" =>  date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                        'form_request_id' => $request_id[0]->request_id
                                    ]);
                                    $status = 200;
                                    $message = "FTO No Is Added !";
                                } catch (Exception $err) {
                                    $status = 400;
                                    $message = "Error Executed ! Please Try Again ";
                                }
                            } else {
                                $message = "Server Error Try Later ";
                            }
                        }
                    } else {
                        $status = 400;
                        $message = "Form Data Not Approvaed !";
                    }
                } else {
                    $status = 400;
                    $message = "Form data Not Found !";
                }
            }
        } else {
            $status = 400;
            $message = "Form Not Invisible";
        }
        return [$status, $message];
    }
    public function delay_submit_fto(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->submit_fto($request, 'add_dc', 'add_delay_fto');
            return response()->json(['status' => $data[0], 'message' => $data[1]]);
        }
    }
    public function unemp_submit_fto(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->submit_fto($request, 'add_unemp_allowance', 'add_unemp_fto');
            return response()->json(['status' => $data[0], 'message' => $data[1]]);
        }
    }
}
