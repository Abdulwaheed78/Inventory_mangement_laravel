<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LogController; // to call tghis controller log function
use App\Models\PaymentMode;
use Illuminate\Http\Request;

class PaymentModeController extends Controller
{
    public function index()
    {
        $modes = PaymentMode::where('deleted','no')->get();
        return view('admin.paymentmodes.index', compact('modes'));
    }

    public function create()
    {
        return view('admin.paymentmodes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:payment_modes,name|max:100',
            'initial' => 'required|unique:payment_modes,initial|max:100',
        ]);

        $payment=PaymentMode::create([
            'name' => $request->name,
            'initial' => $request->initial,
            'qr' => 'null',
        ]);
        app( abstract: LogController::class)->insert('insert', 'payment_mode', auth()->id(), $payment->id);

        return redirect()->route('modes.index')->with('success', 'Payment Mode created successfully.');
    }

    public function edit($id)
    {
        $stage = PaymentMode::findOrFail($id);
        return view('admin.paymentmodes.edit', compact('stage'));
    }

    // Update the specified stages in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:payment_modes,name,' . $id . '|max:100',
            'initial' => 'required|unique:payment_modes,initial,' . $id . '|max:100',
        ]);

        $stage = PaymentMode::findOrFail($id);
        $stage->update([
            'name' => $request->name,
            'initial' => $request->initial,
        ]);
        app(LogController::class)->insert('update', 'payment_mode', auth()->id(), $id);

        return redirect()->route('modes.index')->with('success', 'Payment Mode updated successfully.');
    }

    // Remove the specified stages from storage
    public function destroy($id)
    {
        $stage = PaymentMode::findOrFail($id);
        $stage->update([
            'deleted'=>'yes',
        ]);
        app(LogController::class)->insert('delete', 'payment_mode', auth()->id(), $id);

        return redirect()->route('modes.index')->with('success', 'Payment Mode deleted successfully.');
    }
}
