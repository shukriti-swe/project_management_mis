<?php

namespace App\Http\Controllers;

use App\Models\LayerType;
use Illuminate\Http\Request;

class LayerTypeController extends Controller
{
    public function index()
    {
        $layerTypes = LayerType::all();
        return view('admin.layer-types.index', compact('layerTypes'));
    }

    public function create()
    {
        return view('admin.layer-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:layer_types,title',
            'status' => 'required|boolean',
        ]);

        LayerType::create($request->only('title', 'status'));

        return redirect()->route('layerType.index')->with('success', 'Layer type created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $layerType = LayerType::findOrFail($id);
        return view('admin.layer-types.edit', compact('layerType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:layer_types,title,' . $id,
            'status' => 'required|boolean',
        ]);

        $layerType = LayerType::findOrFail($id);
        $layerType->update($request->only('title', 'status'));

        return redirect()->route('layerType.index')->with('success', 'Layer type updated successfully.');
    }

    public function destroy($id)
    {
        $layerType = LayerType::findOrFail($id);
        $layerType->delete();
        return redirect()->route('layerType.index')->with('success', 'Layer type deleted successfully.');
    }
}
