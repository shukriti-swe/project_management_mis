<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Project;
use App\Models\User;
use App\Services\LayerService;
use Illuminate\Http\Request;
use Throwable;

class LayerController extends Controller
{

    public function __construct(
        protected LayerService $layerService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo "<pre>";

        function printTree($nodes, $prefix = '')
        {
            foreach ($nodes as $node) {

                echo $prefix . "├── " . $node->name . "\n";

                if ($node->children->isNotEmpty()) {
                    printTree($node->children, $prefix . "│   ");
                }
            }
        }

        $tree = Layer::get()->toTree();

        printTree($tree);

        echo "</pre>";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::all();
        $project = Project::findOrFail($request->input('project'));
        $statuses = $project->statuses()->get();
        $parentLayers = Layer::orderBy('created_at', 'desc')->get();
        $parent = Layer::find($request->input('parent'));
        return view('admin.layers.create',
            compact(
                'project',
                'parentLayers',
                'statuses',
                'parent',
                'users'
            ));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'nullable|exists:statuses,id',
                'project_id' => 'required|exists:projects,id',
                'type' => 'required|in:task,container',
                'parent_id' => 'nullable|exists:layers,id',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date|after_or_equal:start_time',

                'users' => 'nullable|array',
                'users.*' => 'exists:users,id'
            ]);

            $this->layerService->createLayer($validated);

            if ($request->parent_id) {
                return redirect()
                    ->route('layer.show', $request->parent_id)
                    ->with('success', 'Layer has been created.');
            }

            return redirect()
                ->route('projectDetails', $request->project_id)
                ->with('success', 'Layer has been created.');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Layer $layer)
    {
        $layer->load([
            'children.status',
            'status',
            'project',
            'ancestors',
            'users'
        ]);

//        dd($layer->users->first()->pivot);

        $statuses = $layer->project->statuses;

        $ancestors = $layer->ancestors;

        return view('admin.layers.show', compact(
            'layer',
            'statuses',
            'ancestors'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Layer $layer)
    {
        $users = User::all();
        $project = Project::findOrFail($layer->project_id);
        $statuses = $project->statuses()->get();
        $parent = Layer::find($layer->parent_id);
        return view('admin.layers.edit', compact('project', 'statuses', 'parent', 'layer', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, Layer $layer)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'nullable|exists:statuses,id',
                'project_id' => 'required|exists:projects,id',
                'type' => 'required|in:task,container',
                'parent_id' => 'nullable|exists:layers,id',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date|after_or_equal:start_time',

                'users' => 'nullable|array',
                'users.*' => 'exists:users,id'
            ]);

            $this->layerService->updateLayer($layer, $validated);

            return redirect()
                ->route('layer.show', $layer->id)
                ->with('success', 'Layer has been updated.');

        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Layer $layer, $status)
    {
        try {
            $this->layerService->changeStatus($layer, $status);

            return back()->with('success', 'Status updated successfully.');

        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layer $layer)
    {
        try {
            $this->layerService->deleteLayer($layer);

            if ($layer->parent_id) {
                return redirect()
                    ->route('layer.show', $layer->parent_id)
                    ->with('success', 'Layer has been deleted.');
            }

            return redirect()
                ->route('projectDetails', $layer->project_id)
                ->with('success', 'Layer has been deleted.');

        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
