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

class ReportController extends Controller
{
    public function projectSammary(){
        $projects = Project::withCount('layers')->orderBy('id', 'desc')->get();
        $users = User::all();
        $statuses = Status::all();
        return view('admin.reports.project_sammary', compact('projects','users','statuses'));
    }

    public function projectReport($id) {
        $project = Project::with(['layers.users', 'layers.status'])->findOrFail($id);
    
        return view('admin.reports.project_report', compact('project'));
    }



    // ============ Start project with child =======================

    public function projectWithLayers()
    {
        $projects = Project::with([
            'user',
            'layers' => function($query) {
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

    public function editProject($id) {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        return response()->json($project); 
    }
    
    public function updateProject(Request $request) {
        $project = Project::findOrFail($request->project_id);
        $project->update([
            'title' => $request->title,
            'user_id' => $request->user_id,
            'status_id' => $request->status_id,
        ]);
        return response()->json(['status' => 'success']);
    }
    
    public function destroyProject($id) {
        $project = Project::findOrFail($id);
        $project->layers()->delete(); 
        $project->delete();
        return response()->json(['status' => 'success']);
    }

    public function storeProjectChild(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required',
            'status_id' => 'required',
        ]);

        $lastPosition = Layer::where('project_id', $request->project_id)
                        ->where('parent_id', $request->parent_id)
                        ->max('position') ?? 0;

        $layer = new Layer();
        $layer->name = $request->name;
        $layer->project_id = $request->project_id;
        $layer->parent_id = $request->parent_id; 
        $layer->start_time = $request->start_time;
        $layer->end_time = $request->end_time;
        $layer->status_id = $request->status_id;
        $layer->position = $lastPosition + 1;
        $layer->save();

        // Multiple Users Sync with Pivot Data
        if ($request->has('user_ids')) {
            $syncData = [];
            foreach ($request->user_ids as $userId) {
                $syncData[$userId] = [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ];
            }
            $layer->users()->sync($syncData);
        }

        return response()->json(['status' => 'success', 'message' => 'Layer added successfully!']);
    }

    public function editProjectChild($id)
    {
        $layer = Layer::with('users')->findOrFail($id);
        return response()->json($layer);
    }

    public function updateProjectChild(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status_id' => 'required',
        ]);

        $layer = Layer::findOrFail($request->layer_id);
        $layer->name = $request->name;
        $layer->start_time = $request->start_time;
        $layer->end_time = $request->end_time;
        $layer->status_id = $request->status_id;
        $layer->save();

        // Update Users
        if ($request->has('user_ids')) {
            $syncData = [];
            foreach ($request->user_ids as $userId) {
                $syncData[$userId] = [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ];
            }
            $layer->users()->sync($syncData);
        } else {
            $layer->users()->detach(); // যদি সব ইউজার রিমুভ করে দেওয়া হয়
        }

        return response()->json(['status' => 'success', 'message' => 'Layer updated successfully!']);
    }
    
    //drag and drop
    public function reorderLayers(Request $request)
    {
        $hierarchy = $request->hierarchy;
        
        foreach ($hierarchy as $index => $item) {
            $this->processLayerOrdering($item, null, $index + 1);
        }

        return response()->json(['status' => 'success']);
    }

    private function processLayerOrdering($item, $parentId, $position)
    {
        $layerId = is_numeric($item['id']) ? $item['id'] : null;

        if ($layerId) {
            $layer = Layer::find($layerId);
            if ($layer) {
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

    

    public function updateDatesAjax(Request $request)
    {
        $request->validate([
            'layer_id' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $layer = Layer::findOrFail($request->layer_id);
        $layer->start_time = $request->start_time;
        $layer->end_time = $request->end_time;
        $layer->save();

        // response::json এর বদলে response()->json ব্যবহার করুন
        return response()->json([
            'status' => 'success',
            'message' => 'Dates updated successfully!',
            'new_start' => date('d M, Y', strtotime($layer->start_time)),
            'new_end' => date('d M, Y', strtotime($layer->end_time))
        ]);
    }

}