<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class GoogleMapController extends Controller
{
    function getUserLocationMap(Request $request)
    {
        $login = LoginLog::findOrFail(decrypt($request->lid));
        return view('user-location-map', compact('login'));
    }
}
