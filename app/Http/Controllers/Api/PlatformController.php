<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
 

    public function index()
    {
        return response()->json([
            'platforms' => Platform::all(),
            'user_platforms' => request()->user()->platforms
        ]);
    }

    public function toggle(Request $request, Platform $platform)
    {
        $user = $request->user();
        $user->platforms()->toggle($platform);
        
        return response()->json([
            'message' => 'Platform preference updated',
            'user_platforms' => $user->platforms
        ]);
    }
}