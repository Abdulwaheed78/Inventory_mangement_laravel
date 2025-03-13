<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::where('deleted','no')->orderBy('id', 'desc')->get();
        return view('admin.warehouses.index', compact('warehouses')); // Return to a view
    }

    // Show the form for creating a new Warehouse
    public function create()
    {
        return view('admin.warehouses.create');
    }

    // Store a newly created Warehouse in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:warehouses,name|max:50',
            'location' => 'required|unique:warehouses,location|string|max:255',
        ]);

        $ware = Warehouse::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        app(LogController::class)->insert('insert', 'warehouse', auth()->id(), $ware->id);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    // Display the specified Warehouse
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    // Show the form for editing the specified Warehouse
    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    // Update the specified Warehouse in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id . '|max:50', // Update unique validation to exclude current category
            'location' => 'nullable|unique:warehouses,location,' . $id . '|string|max:255', // Update unique validation to exclude current warehouse
        ]);


        $Warehouse = Warehouse::findOrFail($id);
        $Warehouse->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        app(LogController::class)->insert('update', 'warehouse', auth()->id(), $id);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    // Remove the specified Warehouse from storage
    public function destroy($id)
    {
        $Warehouse = Warehouse::findOrFail($id);
        $Warehouse->update([
            'deleted'=>'yes',
        ]);
        app(LogController::class)->insert('delete', 'warehouse', auth()->id(), $id);
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
