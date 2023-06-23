<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Province;
use Illuminate\Http\Request;

class LocalityProvinceController extends Controller
{
    public function get_localities(Request $request)
    {
        $request->validate(['province_id']);

        $province_id = $request->province_id ?? 1;
        $localities = Locality::where('province_id', $province_id)->get();
        
        return response(compact("localities"));
    }

    public function get_provinces()
    {
        $provinces = Province::all();
        
        return response(compact("provinces"));
    }
}
