<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Project;
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
    public function create()
    {
        $project = Project::first();
        $statuses = $project->statuses()->get();
        $parentLayers = Layer::orderBy('created_at', 'desc')->get();
        return view('admin.layers.create', compact('project', 'parentLayers', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'nullable|exists:statuses,id',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:task,container',
            'parent_id' => 'nullable|exists:layers,id',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $this->layerService->createLayer($validated);

        return redirect()->back()->with('success', 'Layer has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, Layer $layer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'nullable|exists:statuses,id',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:task,container',
            'parent_id' => 'nullable|exists:layers,id',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $this->layerService->updateLayer($layer, $validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
