<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use App\Models\LayerType;
use App\Models\LayerUser;
use App\Services\LayerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use DB;
use Carbon\Carbon;

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
        // dd($layer->project_id);
        $users = User::all();
        // $project = Project::findOrFail($layer->project_id);
        // $statuses = $project->statuses()->get();
        $layerTypes = LayerType::all();

        $parent = Layer::find($layer->parent_id);
        $layers = Layer::whereNotIn('id',$layer->descendants()->pluck('id')->push($layer->id))->orderBy('created_at', 'desc')->get();
        $projects = Project::all();
        $statuses = Status::get();

        return view('admin.layers.edit', compact( 'statuses', 'parent', 'layer', 'users', 'layers', 'projects', 'layerTypes'));
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
//                'type' => 'required|in:task,container',
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
            dd($e->getMessage());
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

    public function layerList(){
        $layers = Layer::all();
        $layerTypes = LayerType::all();
        $projects = Project::all();
        $users     = User::select('id', 'name', 'email')->get();
        $statuses = Status::get();

        return view('admin.layers.layer_list', compact('layers','layerTypes','projects','users','statuses'));
    }

    public function updateLayerStatus(Request $request) {
        $layer = Layer::findOrFail($request->layer_id);
        
        $layer->update([
            'status_id' => $request->status_id
        ]);
    
        return response()->json(['success' => true]);
    }

    public function storeLayer(Request $request)
    {

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'project_id'    => 'required',
            'layer_type_id' => 'required',
            'start_time'    => 'required|date',
            'end_time'      => 'required|date|after_or_equal:start_time',
            'status_id'     => 'required|in:0,1',
            'parent_id'     => 'nullable|exists:layers,id',
        ]);

        $duration = $request->duration;
        if (empty($duration)) {
            $start = Carbon::parse($request->start_time);
            $end   = Carbon::parse($request->end_time);
            $duration = $start->diffInDays($end) + 1;
        }

        $layer = new Layer();
        $layer->name            = $request->name;
        $layer->project_id      = $request->project_id;
        $layer->layer_type_id   = $request->layer_type_id;
        $layer->start_time      = $request->start_time;
        $layer->end_time        = $request->end_time;
        $layer->status_id       = $request->status_id;
        $layer->duration        = $duration;
        

        $layer->parent_id = $request->parent_id ?: null;
        $layer->description     = $request->description ?: null;
        
        $layer->save();

        if ($request->has('assigned_user_ids')) {
            $syncData = [];
            foreach ($request->assigned_user_ids as $userId) {
                $syncData[$userId] = [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ];
            }

            $layer->users()->sync($syncData);
        }

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
            'id'      => $type->id,
            'name'    => $type->title
        ]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:layers,id',
            'column' => 'required|string',
            'value'  => 'nullable'
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
        } else {
            $layer->{$request->column} = $request->value ?: null;
            $layer->save();
        }

        return response()->json(['success' => true]);
    }
}
   