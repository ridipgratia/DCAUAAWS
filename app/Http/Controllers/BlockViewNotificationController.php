<?php

namespace App\Http\Controllers;

use App\MyMethod\UsedMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlockViewNotificationController extends Controller
{
    public function index()
    {
        $or_where = ['block_id', NULL];
        $auth_district = Auth::user()->district;
        $auth_block = Auth::user()->block;
        $notifications = UsedMethod::getNotificationBlock($auth_district, $auth_block, $or_where);
        $notifications = UsedMethod::setSentTime($notifications);
        $notifications = UsedMethod::checkNewNotifyBlock($notifications, $auth_district, $auth_block);
        return view("block_view_notification", [
            'notifications' => $notifications
        ]);
    }
    public function block_view_full_notify(Request $request)
    {
        if ($request->ajax()) {
            $notify_id = $_GET['notify_id'];
            $status = 400;
            $message = null;
            $auth_district = Auth::user()->district;
            $auth_block = Auth::user()->block;
            if ($notify_id) {
                if (UsedMethod::checkNotifyBlock($notify_id)) {
                    $result = UsedMethod::viewNotification($notify_id, $auth_district, $auth_block);
                    if ($result[0]) {
                        $notification = UsedMethod::getNotifyBlockId($notify_id);
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
