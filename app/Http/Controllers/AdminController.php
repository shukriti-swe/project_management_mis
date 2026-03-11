<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Project;
// use App\Models\Layer;
use DB;

class AdminController extends Controller
{
    public function projectList(){
        $projects = Project::get()->all();
        return view('admin.project.project_list', compact('projects'));
    }

    public function addProject(){
        
        return view('admin.project.add_project');
    }

    public function saveProject(Request $request){
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            // 'image' => 'required | file | mimes:jpg,png,jpeg| dimensions:max_height=38,max_width=168',
        ]);

        DB::beginTransaction();
        try {

            $project = new Project();
            $project->title       = $request->title;
            $project->description = $request->description;
            $project->start_date  = $request->start_date;
            $project->end_date    = $request->end_date;
            $project->status      = $request->status; 
        
            if($request->hasFile('image')){
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('project'), $imageName);
                $project->image    = $imageName;
            }
            $project->save();
                    
            DB::commit();
            return redirect()->route('projectList')->with('alert-success','New project created successfully !');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('projectList')->with('alert-danger','Something went wrong!');
        }
    }

    public function editProject($id){

        $project = Project::where('id',$id)->first();
        return view('admin.project.edit_project',compact('project'));

    }

    public function updateProject(Request $request){

        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            // 'image' => 'required | file | mimes:jpg,png,jpeg| dimensions:max_height=38,max_width=168',
        ]);

        DB::beginTransaction();
        try {

            $project = Project::where('id',$request->project_id)->first();
            $project->title       = $request->title;
            $project->description = $request->description;
            $project->start_date  = $request->start_date;
            $project->end_date    = $request->end_date;
            $project->status      = $request->status;
            if($request->hasFile('image')){
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('project'), $imageName);
                $project->image    = $imageName;
            }
            $project->save();
                    
            DB::commit();
            return redirect()->route('projectList')->with('alert-success','Project Updated successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('projectList')->with('alert-success','Something went wrong!');
        }
    }

    public function deleteProject($id){
        $project = Project::where('id',$id)->delete();
        return redirect()->route('projectList')->with('alert-success','Project Deleted successfully');

    }

    // public function layerList(){
    //     // $layers = Layer::get()->all();
    //     // return view('admin.layer.layer_list', compact('layers'));
    // }

}