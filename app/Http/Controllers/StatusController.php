<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::with('project')->get();

        return view('admin.statuses.index', compact('statuses'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('admin.statuses.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|in:backlog,todo,in_progress,done,canceled',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        Status::create($request->all());

        return redirect()->route('status.index')->with('success', 'Status created successfully.');
    }

    public function edit(Status $status)
    {
        $projects = Project::all();
        return view('admin.statuses.edit', compact('status', 'projects'));
    }

    public function update(Request $request, Status $status)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|in:backlog,todo,in_progress,done,canceled',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $status->update($request->all());

        //if project is null, make it null
        if ($request->project_id === null) {
            $status->project_id = null;
            $status->save();
        }

        return redirect()->route('status.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(Status $status)
    {
        $status->delete();

        return redirect()->route('status.index')->with('success', 'Status deleted successfully.');
    }
}
