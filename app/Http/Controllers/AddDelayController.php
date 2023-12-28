<?php

namespace App\Http\Controllers;

use App\MyMethod\DelayEmpForm;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddDelayController extends Controller
{
    public function index()
    {
        // Load Delay Copensation Page
        $gp_names = DB::table('gram_panchyats')
            ->where('block_id', Auth::user()->block)
            ->select("gram_panchyat_id", "gram_panchyat_name")
            ->orderBy('gram_panchyat_name', 'asc')
            ->get();
        $district_name = DB::table('districts')->where('district_code', Auth::user()->district)->select('district_name')->get();
        $block_name = DB::table('blocks')->where('block_id', Auth::user()->block)->select('block_name')->get();
        return view('add_delay_form', [
            'gp_names' => $gp_names,
            'district_name' => $district_name[0]->district_name,
            'block_name' => $block_name[0]->block_name
        ]);
    }
    public function create(Request $request)
    {

        // Form Submite Code For Delay Compensation 


        if ($request->ajax()) {
            $gp_name = $request->gp_name;
            $code_number = $request->code_number;
            $mr_number = $request->mr_number;
            $person_delay = $request->person_delay;
            $designation_delay = $request->designation_delay;
            $recover_amount = $request->recover_amount;
            $date_recover_amount = $request->date_recover_amount;
            $date_deposite_bank = $request->date_deposite_bank;
            $bank_statement_url = $request->file('bank_statement');
            $emp_code = Auth::user()->login_id;
            $status = null;
            $message = null;
            $check_form_pendding = DelayEmpForm::checkFormPending('add_dc');
            if ($check_form_pendding) {
                $error_message = [
                    'required' => 'Fill Your Basic Details',
                    'mimes' => 'Select Only PDF'
                ];
                $validator = Validator::make(
                    $request->all(),
                    [
                        'gp_name' => 'required',
                        'code_number' => 'required',
                        'mr_number' => 'required',
                        'person_delay' => 'required',
                        'designation_delay' => 'required',
                        'recover_amount' => 'required',
                        'date_recover_amount' => 'required',
                        'date_deposite_bank' => 'required',
                        'bank_statement' => 'required|mimes:pdf',
                    ],
                    $error_message,
                );
                if ($validator->fails()) {
                    $status = 400;
                    $message = $error_message['required'];
                } else {
                    date_default_timezone_set('Asia/Kolkata');
                    $submited_date = date("Y-m-d");
                    $submited_year = date('Y');
                    $submited_month = date('m');
                    if ($date_recover_amount < $submited_date && $date_deposite_bank < $submited_date) {
                        $last_id = DB::table('add_dc')->orderBy('id', 'desc')->first();
                        if ($last_id == null) {
                            $last_id = 1;
                        } else {
                            $last_id = $last_id->id + 1;
                        }
                        $request_id = "DC" . '/' . $submited_year . '/' . $emp_code . '/' . $last_id;
                        $check_error = false;
                        $message = $request_id;
                        $temp_bank_statement_url = null;
                        try {
                            $temp_bank_statement_url = $bank_statement_url->store('public/images/' . $emp_code . '/dc_pdf');
                            $check_error = false;
                        } catch (Exception $error) {
                            $check_error = true;
                        }

                        if ($check_error) {
                            $status = 400;
                            $message = "Please Try Later Problem At PDf Upload";
                        } else {
                            $gp_id = $gp_name;
                            $district_id = null;
                            $block_id = null;
                            if (isset(Auth::user()->district)) {
                                $district_id = Auth::user()->district;
                            }
                            if (isset(Auth::user()->block)) {
                                $block_id = Auth::user()->block;
                            }
                            try {
                                DB::table('delay_form_status')
                                    ->insert([
                                        'form_request_id' => $request_id,
                                    ]);
                                $check_error = true;
                            } catch (Exception $err) {
                                $check_error = false;
                            }
                            if ($check_error) {
                                try {
                                    DB::table('add_dc')->insert([
                                        'submited_by' => $emp_code,
                                        'code_number' => $code_number,
                                        'mr_number' => $mr_number,
                                        'person_delay' => $person_delay,
                                        'designation_delay' => $designation_delay,
                                        'recover_amount' => $recover_amount,
                                        'date_recover_amount' => $date_recover_amount,
                                        'date_deposite_bank' => $date_deposite_bank,
                                        'bank_statement_url' => $temp_bank_statement_url,
                                        'date_of_submit' => $submited_date,
                                        'year_of_submit' => $submited_year,
                                        'month_of_submit' => $submited_month,
                                        'request_id' => $request_id,
                                        'approval_status' => 1,
                                        'district_id' => $district_id,
                                        'block_id' => $block_id,
                                        'gp_id' => $gp_id,
                                        "created_at" =>  date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s')
                                    ]);
                                    $check_error = false;
                                } catch (Exception $error) {
                                    $check_error = true;
                                }
                                if ($check_error) {
                                    $status = 400;
                                    $message = "Please Try Later Problem At database";
                                } else {
                                    $status = 200;
                                    $message = "Form Successfully Submited";
                                }
                            } else {
                                $status = 400;
                                $message = "Please try Later Problem At database";
                            }
                        }
                    } else {
                        $status = 400;
                        $message = "You Do Not Select Future Date ";
                    }
                    // if ($check_error) {
                    //     $status = 400;
                    //     $message = "Please Try Later Problem At database";
                    // } else {
                    //     $status = 200;
                    //     $message = "Ok";
                    // }
                }
            } else {
                $status = 400;
                $message = "Already A Form Pendding !";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
