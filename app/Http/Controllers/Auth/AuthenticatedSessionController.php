<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('clients')->attempt($request->only('email', 'password'))) {
            $client = Auth::guard('clients')->user();
            $token = $client->createToken('token_name')->plainTextToken;

            return response()->json(['token' => $token]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $courier = Courier::where('email', $request->email)->first();

        if (!$courier || !Hash::check($request->password, $courier->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        $token = $courier->createToken('CourierApp')->plainTextToken;

        return response()->json(['token' => $token]);
    }


    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
