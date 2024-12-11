<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('courier')->get();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        $payment = Payment::create([
            'courier_id' => $request->courier_id,
            'total_amount' => $request->total_amount,
            'payment_date' => $request->payment_date,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Payment created successfully', 'payment' => $payment]);
    }

    public function complete($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'completed']);

        return response()->json(['message' => 'Payment completed', 'payment' => $payment]);
    }
}
