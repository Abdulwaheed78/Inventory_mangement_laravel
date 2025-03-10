<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // Display a list of categories
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.customers.index', compact('customers')); // Return to a view
    }

    // Show the form for creating a new category
    public function create()
    {
        return view('admin.customers.create');
    }

    // Store a newly created customer in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:customers,email|max:50',
            'phone' => 'required|unique:customers,phone|max_digits:10',
            'address' => 'nullable|string|max:255',
        ]);

        $c = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        app(LogController::class)->insert('insert', 'customers', auth()->id(), $c->id);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    // Display the specified customer
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // Update the specified customer in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:customers,email,' . $id . '|max:50',
            'phone' => 'required|unique:customers,phone,' . $id . '|max_digits:10',
            'address' => 'nullable|string|max:255',
        ]);
        $customer = Customer::findOrFail($id);
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        app(LogController::class)->insert('update', 'customers', auth()->id(), $id);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    // Remove the specified customer from storage
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        app(LogController::class)->insert('delete', 'customers', auth()->id(), $id);
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }


    public function search(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::where('name', 'LIKE', "%{$search}%")
            ->limit(10)  // Limit results
            ->get(['id', 'name']);

        return response()->json($customers);
    }
}
