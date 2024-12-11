<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Courier;

class AuthCouriersController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('courier')->attempt($credentials)) {
            $courier = Auth::guard('courier')->user();
            return response()->json(['message' => 'Login successful', 'courier' => $courier]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('courier')->logout();
        return response()->json(['message' => 'Logout successful']);
    }

    public function profile(Request $request)
    {
        $courier = Auth::guard('courier')->user();
        return response()->json($courier);
    }
}

