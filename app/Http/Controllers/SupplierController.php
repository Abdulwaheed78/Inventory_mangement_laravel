<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // Display a list of suppliers
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();
        return view('admin.suppliers.index', compact('suppliers')); // Return to a view
    }

    // Show the form for creating a new suppliers
    public function create()
    {
        return view('admin.suppliers.create');
    }

    // Store a newly created suppliers in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:suppliers,email|max:50',
            'phone' => 'required|unique:suppliers,phone|max_digits:10',
            'address' => 'nullable|string|max:255',
        ]);

        $sup = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        app(LogController::class)->insert('insert', 'suppliers', auth()->id(), $sup->id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    // Display the specified suppliers
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    // Show the form for editing the specified suppliers
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    // Update the specified customer in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:suppliers,email,' . $id . '|max:50',
            'phone' => 'required|unique:suppliers,phone,' . $id . '|max_digits:10',
            'address' => 'nullable|string|max:255',
        ]);
        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        app(LogController::class)->insert('update', 'suppliers', auth()->id(), $id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Remove the specified customer from storage
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        app(LogController::class)->insert('delete', 'suppliers', auth()->id(), $id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
