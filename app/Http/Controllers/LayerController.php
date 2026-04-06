<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use App\Models\LayerType;
use App\Services\LayerService;
use App\Services\LayerPropagationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class LayerController extends Controller
{

    public function __construct(
        protected LayerService             $layerService,
        protected LayerPropagationService $statusService
    )
    {
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        Layer::all()->each(function ($layer) {
            if (!$layer->children()->exists()) {
                app(LayerPropagationService::class)
                    ->updateTaskStatus($layer, $layer->status_id);
            }
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::all();
        $project = Project::find($request->input('project'));
        $projects = Project::all();
        $statuses = Status::all();
        $parentLayers = Layer::orderBy('created_at', 'desc')->get();
        $parent = isset($request->parent) ? Layer::find($request->input('parent')) : null;
        $layerTypes = LayerType::all();
        return view('admin.layers.create',
            compact(
                'project',
                'projects',
                'parentLayers',
                'statuses',
                'parent',
                'users',
                'layerTypes'
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
                'layer_type_id' => 'required|exists:layer_types,id',
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
            'users',
            'layerType'
        ]);

        $statuses = Status::all();

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
        $layerTypes = LayerType::all();

        $parent = Layer::find($layer->parent_id);
        $layers = Layer::whereNotIn('id', $layer->descendants()->pluck('id')->push($layer->id))->orderBy('created_at', 'desc')->get();
        $projects = Project::all();
        $statuses = Status::get();

        return view('admin.layers.edit', compact('statuses', 'parent', 'layer', 'users', 'layers', 'projects', 'layerTypes'));
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
                'layer_type_id' => 'required|exists:layer_types,id',
                'project_id' => 'required|exists:projects,id',
                'parent_id' => 'nullable|exists:layers,id',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date|after_or_equal:start_time',

                'users' => 'nullable|array',
                'users.*' => 'exists:users,id'
            ]);

            $this->layerService->updateLayer($layer, $validated);

            return redirect()
                ->back()
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
                ->back()
                ->with('success', 'Layer has been deleted.');

        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function layerList()
    {
        $layers = Layer::all();
        $layerTypes = LayerType::all();
        $projects = Project::all();
        $users = User::select('id', 'name', 'email')->get();
        $statuses = Status::get();

        return view('admin.layers.layer_list', compact('layers', 'layerTypes', 'projects', 'users', 'statuses'));
    }

    public function updateLayerStatus(Request $request)
    {
        $request->validate([
            'layer_id' => 'required|exists:layers,id',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $layer = Layer::findOrFail($request->layer_id);

        $this->layerService->changeStatus($layer, $request->status_id);

        return response()->json(['success' => true]);
    }

    public function storeLayer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'layer_type_id' => 'required|exists:layer_types,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'status_id' => 'nullable|exists:statuses,id',
            'parent_id' => 'nullable|exists:layers,id',
            'description' => 'nullable|string',
            'assigned_user_ids' => 'nullable|array',
            'assigned_user_ids.*' => 'exists:users,id',
        ]);

        // -------------------------
        // 1. KEEP your duration logic
        // -------------------------
        $duration = $request->duration;

        if (empty($duration)) {
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);
            $duration = $start->diffInDays($end) + 1;
        }

        $validated['duration'] = $duration;

        // -------------------------
        // 2. Map users for service
        // -------------------------
        $validated['users'] = $request->assigned_user_ids ?? [];

        // -------------------------
        // 3. Delegate to service
        // -------------------------
        $this->layerService->createLayer($validated);

        return response()->json(['success' => true]);
    }

    public function updateLayerType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:layer_types,title'
        ]);

        $type = LayerType::create(['title' => $validated['name']]);

        return response()->json([
            'success' => true,
            'id' => $type->id,
            'name' => $type->title
        ]);
    }

    /**
     * @throws Throwable
     */
    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:layers,id',
            'column' => 'required|string',
            'value' => 'nullable'
        ]);

        $layer = Layer::findOrFail($request->id);

        if ($request->column === 'assigned_user_ids') {

            $syncData = [];

            if (!empty($request->value)) {
                foreach ($request->value as $userId) {
                    $syncData[$userId] = [
                        'assigned_by' => auth()->id(),
                        'assigned_at' => now(),
                    ];
                }
            }

            $layer->users()->sync($syncData);

        } elseif ($request->column === 'status_id') {

            $this->layerService->changeStatus($layer, $request->value);

        } elseif ($request->column === 'parent_id') {

            $this->layerService->updateLayer($layer, [
                'parent_id' => $request->value
            ]);

        } else {

            $layer->{$request->column} = $request->value ?: null;
            $layer->save();
        }

        return response()->json(['success' => true]);
    }

    public function updateLayerSchedule(Request $request, Layer $layer)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
        ]);

        $layer->update($validated);

        return response()->json(['success' => true]);
    }

    public function board()
    {
        $projects = Project::all();
        $statuses = Status::all();
        $users = User::all();
        $parentLayers = Layer::all();

        // map status to include layer count for that status
        $statuses = $statuses->map(function ($status) {
            $status->layer_count = Layer::where('status_id', $status->id)->count();
            return $status;
        });
        return view('admin.reports.board', compact('projects', 'statuses', 'users', 'parentLayers'));
    }

    public function boardData(Request $request)
    {
        $projectId = $request->project_id;

        $query = Layer::with([
            'status:id,label,category,color',
            'users:id,name',
        ])->defaultOrder();

        // apply project filter
        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $layers = $query->get();

        $data = $layers->map(function ($layer) {
            return [
                'id' => $layer->id,
                'name' => $layer->name,

                // hierarchy
                'parent_id' => $layer->parent_id,

                // status
                'status_id' => $layer->status_id,
                'status' => $layer->status ? [
                    'id' => $layer->status->id,
                    'label' => $layer->status->label,
                    'category' => $layer->status->category ?? null,
                    'color' => $layer->status->color ?? null,
                ] : null,

                // dates
                'start_time' => optional($layer->start_time)->format('Y-m-d H:i:s'),
                'end_time' => optional($layer->end_time)->format('Y-m-d H:i:s'),

                // progress
                'progress_percent' => $layer->progress_percent ?? 0,
                'total_tasks' => $layer->total_tasks ?? 0,
                'completed_tasks' => $layer->completed_tasks ?? 0,

                // users
                'users' => $layer->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar ?? null,
                    ];
                })->values(),

                // helper
                'is_leaf' => $layer->isLeaf(),
            ];
        });

        return response()->json([
            'layers' => $data
        ]);
    }

    public function parentLayers(Request $request)
    {
        $projectId = $request->project_id;

        $layers = Layer::where('project_id', $projectId)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'layers' => $layers
        ]);
    }

    public function layerDetailJson($id)
    {
        $layer = Layer::with([
            'status',
            'users',
            'ancestors',
        ])->findOrFail($id);

        $root = $layer->ancestors()
            ->defaultOrder()
            ->first()
            ?? $layer;

        $tree = Layer::with(['status', 'users'])
            ->descendantsAndSelf($layer->id)
            ->toTree();

        return response()->json([
            'layer' => $layer,
            'tree' => $tree
        ]);
    }
}
   