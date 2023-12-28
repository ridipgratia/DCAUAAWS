<?php

namespace App\Http\Controllers\district;

use App\Http\Controllers\Controller;
use App\MyMethod\UsedMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    public function index()
    {
        $or_where = ['district_id', 999];
        $notifications = UsedMethod::getNotifications(Auth::user()->district, $or_where);
        $notifications = UsedMethod::setSentTime($notifications);
        $notifications = UsedMethod::checkNewNotify($notifications, Auth::user()->district, 'district_code');
        return view('district.notification', [
            'notifications' => $notifications
        ]);
    }
    public function view_full_notification(Request $request)
    {
        if ($request->ajax()) {
            $notify_id = $_GET['notify_id'];
            $status = 400;
            $message = null;
            $district_id = Auth::user()->district;
            $block_id = null;
            if ($notify_id) {
                if (UsedMethod::checkNotify($notify_id)) {
                    $result = UsedMethod::viewNotification($notify_id, $district_id, $block_id);
                    if ($result[0]) {
                        $notification = UsedMethod::getNotifyId($notify_id);
                        if ($notification[0]->document) {
                            $file_url = Storage::url($notification[0]->document);
                            $notification[0]->document = $file_url;
                        }
                        $message = $notification;
                        $status = 200;
                    } else {
                        $message = $result[1];
                    }
                } else {
                    $message = "Notification Not Found !";
                }
            } else {
                $message = "Notification Not Found !";
            }
            return response()->json(['status' => $status, 'message' => $message]);
        }
    }
}
