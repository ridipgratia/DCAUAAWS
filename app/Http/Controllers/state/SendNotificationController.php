<?php

namespace App\Http\Controllers\state;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\MyMethod\StateMethod;
use Illuminate\Support\Facades\Storage;

class SendNotificationController extends Controller
{
    public function index()
    {
        $districts = DB::table('districts')
            ->select('district_code', 'district_name')
            ->orderBy('district_name', 'asc')
            ->get();
        $notifications = StateMethod::getAllNotification();
        return view('state.send_notification', [
            'districts' => $districts,
            'notifications' => $notifications
        ]);
    }
    public function store_notification(Request $request)
    {
        if ($request->ajax()) {
            $district_code = $request->district_code;
            $block_code = $request->block_code;
            $notify_file = $request->notify_file;
            $notify_text = $request->notify_text;
            $notify_url = $request->file('notify_file');
            $status = 400;
            $message = null;
            if (empty($notify_text)) {
                $message = "Type Your Notification ";
            } else {
                $check_file = false;
                $check = true;
                $error_message = [
                    'mimes' => 'Upload Only PDF File',
                    'max' => 'Upload File Should Be 5 mb'
                ];
                if ($request->hasFile('notify_file')) {
                    $check_file = true;
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'notify_file' => 'mimes:pdf|max:500'
                        ],
                        $error_message
                    );
                    if ($validator->fails()) {
                        $check = false;
                        $message = $error_message['mimes'] . ' ' . $error_message['max'];
                    } else {
                        $check = true;
                    }
                }
                $file_url = null;
                if ($check) {
                    if ($check_file) {
                        try {
                            $file_url = $notify_url->store('public/images/notify/');
                            $check = true;
                        } catch (Exception $e) {
                            $check = false;
                        }
                    }
                    if ($check) {
                        try {
                            $today = date('Y-m-d');
                            DB::table('notification')
                                ->insert([
                                    'district_id' => $district_code,
                                    'block_id' => $block_code,
                                    'description' => $notify_text,
                                    'document' => $file_url,
                                    'date' => $today,
                                    "created_at" =>  date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s')
                                ]);
                            $check = true;
                        } catch (Exception $e) {
                            $check = false;
                        }
                        if ($check) {
                            $message = "Notification Upload Successfully";
                            $status = 200;
                        } else {
                            Storage::delete($file_url);
                            $message = "Error Execute While Upload Data !";
                        }
                    } else {
                        $message = "Error Execute While File Uploading !";
                    }
                }
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
    public function view_notification(Request $request)
    {
        if ($request->ajax()) {
            $status = null;
            $message = null;
            $notify_id = $_GET['notify_id'];
            $notification = StateMethod::getNotificationById($notify_id);
            if ($notification) {
                $status = 200;
                if ($notification[0]->document) {
                    $file_url = Storage::url($notification[0]->document);
                    $notification[0]->document = $file_url;
                }
            } else {
                $status = 400;
                $notification = "Error Execute While Fetching Data ";
            }
            return response()->json(['status' => $status, 'message' => $notification]);
        }
    }
    public function remove_notification(Request $request)
    {
        if ($request->ajax()) {
            $status = 400;
            $message = null;
            $notify_id = $_GET['notify_id'];
            if (StateMethod::checkNotify($notify_id)) {
                if (StateMethod::removeNotify($notify_id)) {
                    $status = 200;
                    $message = "Notification Removed !";
                } else {
                    $message = "Try Later ! Problem In Database !";
                }
            } else {
                $message = "Notification Not Found !";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
