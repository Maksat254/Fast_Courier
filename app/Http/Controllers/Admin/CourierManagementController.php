<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Courier;
use Illuminate\Support\Facades\Hash;

class CourierManagementController extends Controller
{
    public function index()
    {
        return Courier::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:couriers',
            'password' => 'required|min:6',
        ]);

        $courier = Courier::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Courier created successfully', 'courier' => $courier]);
    }

    public function destroy($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->delete();

        return response()->json(['message' => 'Courier deleted successfully']);
    }
}
