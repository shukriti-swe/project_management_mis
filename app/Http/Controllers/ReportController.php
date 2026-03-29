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
        return view('admin.reports.project_sammary', compact('projects','users'));
    }

    public function projectReport($id) {
        $project = Project::with(['layers.users', 'layers.status'])->findOrFail($id);
    
        return view('admin.reports.project_report', compact('project'));
    }

}