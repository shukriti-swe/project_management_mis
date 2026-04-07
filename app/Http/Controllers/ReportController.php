<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Project;
use App\Models\User;
use App\Models\Status;
use App\Services\LayerService;
use App\Services\LayerPropagationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct(
        protected LayerService            $layerService,
        protected LayerPropagationService $statusUpdate
    )
    {
    }

    protected array $affectedParents = [];

    public function projectSammary()
    {
        $projects = Project::withCount('layers')->orderBy('id', 'desc')->get();
        $users = User::all();
        $statuses = Status::all();
        return view('admin.reports.project_sammary', compact('projects', 'users', 'statuses'));
    }

    public function projectReport($id)
    {
        $project = Project::with(['layers.users', 'layers.status'])->findOrFail($id);

        return view('admin.reports.project_report', compact('project'));
    }


    // ============ Start project with child =======================

    public function projectWithLayers()
    {
        $projects = Project::with([
            'user',
            'layers' => function ($query) {
                $query->whereNull('parent_id')->orderBy('position');
            },
            'layers.users',
            'layers.status'
        ])->get();

        $statuses = Status::all();
        $users = User::all();
        return view('admin.reports.project_layers', compact('projects', 'statuses', 'users'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status_id' => 'required',
            'user_id' => 'required',
            'parent_id' => 'nullable|exists:projects,id',
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->status_id = $request->status_id;
        $project->start_date = $request->start_time;
        $project->end_date = $request->end_time;
        $project->user_id = $request->user_id;
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully!'
        ]);
    }

    public function editProject($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        return response()->json($project);
    }

    public function updateProject(Request $request)
    {
        $project = Project::findOrFail($request->project_id);
        $project->update([
            'title' => $request->title,
            'user_id' => $request->user_id,
            'status_id' => $request->status_id,
        ]);
        return response()->json(['status' => 'success']);
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        $project->layers()->delete();
        $project->delete();
        return response()->json(['status' => 'success']);
    }

    public function updateDates(Request $request) {
        Project::find($request->project_id)->update([
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);
        return response()->json(['status' => 'success']);
    }

    public function storeProjectChild(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'project_id' => 'required',
                'status_id' => 'required',
                'parent_id' => 'nullable|exists:layers,id',
                'description' => 'nullable|string',
            ]);

            Log::info('Received request to create layer with data: ' . json_encode($validated));

            $validated['users'] = $request->has('user_ids') ? $request->user_ids : [];
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            // Only force startOfDay if no specific time was provided (it's currently at 00:00:00)
            $validated['start_time'] = $start->hour === 0 && $start->minute === 0
                ? $start->startOfDay()
                : $start;

            // Only force endOfDay if no specific time was provided
            $validated['end_time'] = $end->hour === 0 && $end->minute === 0
                ? $end->endOfDay()
                : $end;

            $lastPosition = Layer::where('project_id', $request->project_id)
                ->where('parent_id', $request->parent_id)
                ->max('position') ?? 0;

            $layer = $this->layerService->createLayer($validated);
            $layer->position = $lastPosition + 1;
            $layer->save();

            return response()->json(['status' => 'success', 'message' => 'Layer added successfully!']);
        } catch (Throwable $e) {
            Log::error('Error adding layer: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong while adding the layer.'], 500);
        }

    }

    public function editProjectChild($id)
    {
        $layer = Layer::with('users')->findOrFail($id);
        return response()->json($layer);
    }

    public function updateProjectChild(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'status_id' => 'required',
            ]);

            $validated['users'] = $request->has('user_ids') ? $request->user_ids : [];
            $validated['start_time'] = Carbon::parse($request->start_time)->startOfDay();
            $validated['end_time'] = Carbon::parse($request->end_time)->endOfDay();

            $layer = Layer::findOrFail($request->layer_id);

            $this->layerService->updateLayer($layer, $validated);

            return response()->json(['status' => 'success', 'message' => 'Layer updated successfully!']);
        } catch (Throwable $e) {
            Log::error('Error updating layer: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong while updating the layer.'], 500);
        }
    }

    public function deleteProjectChild($id)
    {
        try {
            $layer = Layer::findOrFail($id);
            if ($layer->children()->count() > 0) {
                $this->recursiveDelete($layer);
            } else {
                $this->layerService->deleteLayer($layer);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Layer and its sub-layers deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @throws \Exception
     */
    private function recursiveDelete($layer)
    {
        foreach ($layer->children as $child) {
            $this->recursiveDelete($child);
        }
        $this->layerService->deleteLayer($layer);
    }

    //drag and drop
    public function reorderLayers(Request $request)
    {
        $this->affectedParents = [];
        $hierarchy = $request->hierarchy;

        foreach ($hierarchy as $index => $item) {
            $this->processLayerOrdering($item, null, $index + 1);
        }

        collect($this->affectedParents)
            ->filter()
            ->unique()
            ->each(function ($parentId) {
                $parent = Layer::find($parentId);
                if ($parent) {
                    $this->statusUpdate->calculate($parent);
                }
            });

        return response()->json(['status' => 'success']);
    }

    private function processLayerOrdering($item, $parentId, $position)
    {
        $layerId = is_numeric($item['id']) ? $item['id'] : null;

        if ($layerId) {
            $layer = Layer::find($layerId);
            if ($layer) {
                $oldParentId = $layer->parent_id;

                // Track affected parents ONLY
                if ($oldParentId != $parentId) {
                    if ($oldParentId) {
                        $this->affectedParents[] = $oldParentId;
                    }

                    if ($parentId) {
                        $this->affectedParents[] = $parentId;
                    }
                }

                $layer->parent_id = $parentId;
                $layer->position = $position;
                $layer->save();
            }
        }

        if (isset($item['children'])) {
            foreach ($item['children'] as $childIndex => $childItem) {
                $this->processLayerOrdering($childItem, $layerId, $childIndex + 1);
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function updateDatesAjax(Request $request)
    {
        $request->validate([
            'layer_id' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $layer = Layer::findOrFail($request->layer_id);
        $this->layerService->updateLayer($layer, [
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'users'      => $layer->users->pluck('id')->toArray(),
        ]);

        // response::json এর বদলে response()->json ব্যবহার করুন
        return response()->json([
            'status' => 'success',
            'message' => 'Dates updated successfully!',
            'new_start' => date('d M, Y', strtotime($layer->start_time)),
            'new_end' => date('d M, Y', strtotime($layer->end_time))
        ]);
    }

}