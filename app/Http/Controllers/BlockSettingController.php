<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlockSettingController extends Controller
{
    // Change Password Page Index
    public function changePasswordIndex(Request $request)
    {
        return view('change_password');
    }
    public function changePassword(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['status' => 400, 'message' => 'Ok']);
        }
    }
}
