<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        return view('state.state_dash');
    }
    public function resetPasswordLoad(Request $request)
    {
        return view('mail_blades.set_reset_password');
    }
}
