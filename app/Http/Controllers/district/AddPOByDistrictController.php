<?php

namespace App\Http\Controllers\district;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddPOByDistrictController extends Controller
{
    public function add_po_index(Request $request)
    {
        $blocks = DB::table('blocks')->select('block_id', 'block_name')->orderBy('block_name', 'asc')->get();
        $districts = DB::table('districts')->select('district_code', 'district_name')
            ->orderBy('district_name', 'asc')
            ->get();
        return view('district.add_po_login', [
            'districts' => $districts,
            'blocks' => $blocks
        ]);
    }
}
