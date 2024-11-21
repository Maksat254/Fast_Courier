<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

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

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
