<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Province;
use Illuminate\Http\Request;

class LocalityProvinceController extends Controller
{
    public function get_localities(Request $request)
    {
        $localities = Locality::with('province')
                    ->when($request->province_id, function ($query) use ($request) {
                        return $query->where('province_id', '<=', $request->province_id);
                    })
                    ->get();
        
        return response(compact("localities"));
    }

    public function get_provinces()
    {
        $provinces = Province::all();
        
        return response(compact("provinces"));
    }
}
