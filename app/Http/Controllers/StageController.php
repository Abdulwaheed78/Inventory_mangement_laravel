<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use App\Models\Stage;

class StageController extends Controller
{
    // Display a list of stages
    public function index()
    {
        $stages = Stage::where('deleted', 'no')->orderBy('id', 'desc')->get();
        return view('admin.stages.index', compact('stages')); // Return to a view
    }

    // Show the form for creating a new stages
    public function create()
    {
        return view('admin.stages.create');
    }

    // Store a newly created stages in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stages,name|max:100',
        ]);

        $st = Stage::create([
            'name' => $request->name,
        ]);
        app(LogController::class)->insert('insert', 'stages', auth()->id(), $st->id);
        return redirect()->route('stages.index')->with('success', 'Stage created successfully.');
    }

    // Display the specified stages
    public function show($id)
    {
        $stage = Stage::findOrFail($id);
        return view('admin.stages.show', compact('stage'));
    }

    // Show the form for editing the specified stages
    public function edit($id)
    {
        $stage = Stage::findOrFail($id);
        return view('admin.stages.edit', compact('stage'));
    }

    // Update the specified stages in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:stages,name,' . $id . '|max:100',
        ]);

        $stage = Stage::findOrFail($id);
        $stage->update([
            'name' => $request->name,
        ]);
        app(LogController::class)->insert('update', 'stages', auth()->id(), $id);
        return redirect()->route('stages.index')->with('success', 'Stage updated successfully.');
    }

    // Remove the specified stages from storage
    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $stage->update([
            'deleted' => 'yes',
        ]);
        app(LogController::class)->insert('delete', 'stages', auth()->id(), $id);
        return redirect()->route('stages.index')->with('success', 'Stage deleted successfully.');
    }
}
