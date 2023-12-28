<?php

namespace App\MyMethod;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsedMethod
{
    // For District User
    public static function getNotifications($identy, $or_where)
    {
        $notifications = DB::table('notification')
            // ->where($or_where[0], $identy)
            // ->orwhere($or_where[0], $or_where[1])
            // ->orderBy('created_at', 'desc')
            // ->get();
            ->where(function ($query) use ($identy) {
                $query->where('district_id', $identy)
                    ->orWhere('district_id', 999);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }

    // For Block User
    public static function getNotificationBlock($district_id, $block_id, $or_where)
    {
        $notifications = DB::table('notification')
            ->where(function ($query) use ($district_id) {
                $query->where('district_id', $district_id)
                    ->orWhere('district_id', 999);
            })
            ->where(function ($query) use ($block_id) {
                $query->where('block_id', $block_id)
                    ->orWhere('block_id', NULL);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return $notifications;
    }
    public static function setSentTime($notifications)
    {
        foreach ($notifications as $notification) {
            $pastDate = Carbon::parse($notification->created_at);
            $currentDate = Carbon::parse(date('Y-m-d H:i:s'));
            $diff = $pastDate->diffForHumans($currentDate);
            // $time = preg_replace("/[^0-9]/", '', $diff);
            $notification->sent_time = $diff;
        }
        return $notifications;
    }
    // For District Users
    public static function checkNewNotify($notifications, $check_id, $column_name)
    {
        foreach ($notifications as $notification) {
            $check_data = DB::table('notify_view')
                ->where('notify_id', $notification->id)
                ->where($column_name, $check_id)
                ->where('block_code', NULL)
                ->select('id')
                ->get();
            if (count($check_data) == 0) {
                $notification->new = "new";
            } else {
                $notification->new = "";
            }
        }
        return $notifications;
    }
    // For Block Users;
    public static function checkNewNotifyBlock($notifications, $district_id, $block_id)
    {
        foreach ($notifications as $notification) {
            $check_data = DB::table("notify_view")
                ->where('notify_id', $notification->id)
                ->where('district_code', $district_id)
                ->where('block_code', $block_id)
                ->select('id')
                ->get();
            if (count($check_data) == 0) {
                $notification->new = "new";
            } else {
                $notification->new = "";
            }
        }
        return $notifications;
    }
    public static function viewNotification($notify_id, $district_id, $block_id)
    {
        $check = true;
        $message = null;
        $notification = DB::table('notify_view')
            ->where('notify_id', $notify_id)
            ->where('district_code', $district_id)
            ->where('block_code', $block_id)
            ->select('id')
            ->get();
        if (count($notification) == 0) {
            try {
                DB::table('notify_view')
                    ->where('notify_id', $notify_id)
                    ->insert([
                        'notify_id' => $notify_id,
                        'district_code' => $district_id,
                        'block_code' => $block_id
                    ]);
                $check = true;
            } catch (Exception $e) {
                $check = false;
                $message = "Error Try Later ";
            }
        }
        return [$check, $message];
    }
    public static function getNotifyId($notify_id)
    {
        $notification = DB::table('notification as notify')
            // ->where('notify.id', $notify_id)
            // ->orWhere('notify.district_id', 999)
            // ->where('notify.district_id', Auth::user()->district)
            // ->select(
            //     'notify.*',
            //     'block_tab.block_name as block_name'
            // )
            // ->leftJoin('blocks as block_tab', 'block_tab.block_id', '=', 'notify.block_id')
            // ->get();
            ->where('notify.id', $notify_id)
            ->where(function ($query) use ($notify_id) {
                $query->where('notify.district_id', Auth::user()->district)
                    ->orWhere('notify.district_id', 999);
            })
            ->select(
                'notify.*',
                'block_tab.block_name as block_name'
            )
            ->leftJoin('blocks as block_tab', 'block_tab.block_id', '=', 'notify.block_id')
            ->get();
        return $notification;
    }
    public static function checkNotify($notify_id)
    {
        $check_notify = DB::table('notification')
            ->where('id', $notify_id)
            ->where(function ($query) {
                $query->where('district_id', Auth::user()->district)
                    ->orWhere('district_id', 999);
            })
            ->select('id')
            ->get();
        if (count($check_notify) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function getNotifyBlockId($notify_id)
    {
        $auth_district = Auth::user()->district;
        $auth_block = Auth::user()->block;
        $notification = DB::table('notification as notify')
            // ->where('notify.id', $notify_id)
            // ->orWhere('notify.district_id', 999)
            // ->where('notify.district_id', Auth::user()->district)
            // ->orwhere('notify.block_id', NULL)
            // ->where('notify.block_id', Auth::user()->block)
            // ->select(
            //     'notify.*',
            //     'block_tab.block_name as block_name'
            // )
            // ->leftJoin('blocks as block_tab', 'block_tab.block_id', '=', 'notify.block_id')
            // ->get();
            ->where('notify.id', $notify_id)
            ->where(function ($query) use ($auth_district) {
                $query->where('notify.district_id', $auth_district)
                    ->orWhere('notify.district_id', 999);
            })
            ->where(function ($query) use ($auth_block) {
                $query->where('notify.block_id', $auth_block)
                    ->orWhere('notify.block_id', NULL);
            })
            ->select(
                'notify.*',
                'block_tab.block_name as block_name'
            )
            ->leftJoin('blocks as block_tab', 'block_tab.block_id', '=', 'notify.block_id')
            ->get();
        return $notification;
    }
    public static function checkNotifyBlock($notify_id)
    {
        $check_notify = DB::table('notification')
            ->where('id', $notify_id)
            ->where(function ($query) {
                $query->where('district_id', Auth::user()->district)
                    ->orWhere('district_id', 999);
            })
            ->where(function ($query) {
                $query->where('block_id', Auth::user()->block)
                    ->orWhere('block_id', NULL);
            })
            ->select('id')
            ->get();
        if (count($check_notify) == 0) {
            return false;
        } else {
            return true;
        }
    }
    // Get new Notification Count For Block 
    public static function getNewNotify($notifications, $district_id, $block_id)
    {
        $get_new = 0;
        foreach ($notifications as $notification) {
            $check_new = DB::table('notify_view')
                ->where('notify_id', $notification->id)
                ->where('district_code', $district_id)
                ->where('block_code', $block_id)
                ->select('id')
                ->get();
            if (count($check_new) == 0) {
                $get_new++;
            }
        }
        return $get_new;
    }
}
