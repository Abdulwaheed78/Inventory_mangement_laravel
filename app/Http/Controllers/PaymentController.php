<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Order;

class PaymentController extends Controller
{

    public function index()
    {
        $suppliers = Payment::with(['order', 'pmode'])->orderBy('id', 'desc')->get();
        return view('admin.payments.index', compact('suppliers')); // Return to a view
    }

    // Show the form for creating a new suppliers
    public function create()
    {
        $modes = PaymentMode::all();
        return view('admin.payments.create', compact('modes'));
    }

    // Store a newly created suppliers in storage
    public function store(Request $request)
    {
        $request->validate([
            'order' => 'required',
            'mode' => 'required',
            'amount' => 'required',
            'date' => 'nullable',
        ]);

        $sup = Payment::create([
            'ordid' => $request->order,
            'pmid' => $request->mode,
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        $order = Order::find($request->order);
        if ($order->total_amount == $request->amount) {
            $order->update([
                'status' => 'Received'
            ]);
        }

        app(LogController::class)->insert('insert', 'payments', auth()->id(), $sup->id);
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function edit($id)
    {
        $modes = PaymentMode::all();
        $supplier = Payment::with(['order','pmode'])->findOrFail($id);
        return view('admin.payments.edit', compact('supplier','modes'));
    }

    // Update the specified customer in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'order' => 'required',
            'mode' => 'required',
            'amount' => 'required',
            'date' => 'nullable',
        ]);

        $sup = Payment::find($id)->update([
            'ordid' => $request->order,
            'pmid' => $request->mode,
            'amount' => $request->amount,
            'date' => $request->date
        ]);

        $order = Order::find($request->order);
        if ($order->total_amount > $request->amount) {
            $order->update([
                'status' => 'Pending'
            ]);
        }else if($order->total_amount == $request->amount){
            $order->update([
                'status' => 'Received'
            ]);
        }
        app(LogController::class)->insert('update', 'payments', auth()->id(), $id);
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    // Remove the specified customer from storage
    public function destroy($id)
    {
        $supplier = Payment::findOrFail($id);
        $supplier->delete();
        app(LogController::class)->insert('delete', 'payments', auth()->id(), $id);
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
