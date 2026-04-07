<?php

use App\Http\Controllers\LayerController;
use App\Http\Controllers\LayerTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Route::middleware(['role:admin'])->group(function () {

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.delete');


    Route::get('/users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit');
    Route::post('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');

    Route::resource('roles', RoleController::class);
    Route::get('/permissions', [RoleController::class, 'permissionList'])->name('permissions.index');
    Route::post('/permissions', [RoleController::class, 'storePermission'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [RoleController::class, 'editPermission'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [RoleController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [RoleController::class, 'deletePermission'])->name('permissions.delete');
    // });

    Route::post('/ckeditor-upload', [ProfileController::class, 'ckeditorUpload'])->name('ckeditor.upload');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::any('/project-list', [AdminController::class, 'projectList'])->name('projectList');
    Route::any('/add-project', [AdminController::class, 'addProject'])->name('addProject');
    Route::any('/save-project', [AdminController::class, 'saveProject'])->name('saveProject');
    Route::any('/edit-project/{id}', [AdminController::class, 'editProject'])->name('editProject');
    Route::any('/update-project', [AdminController::class, 'updateProject'])->name('updateProject');
    Route::any('/delete-project/{id}', [AdminController::class, 'deleteProject'])->name('deleteProject');
    Route::get('/project-details/{project}', [AdminController::class, 'show'])->name('projectDetails');
    Route::patch('/projects/{project}/status', [AdminController::class, 'changeStatus'])->name('changeProjectStatus');

    //Reports
    Route::get('/project-sammary', [ReportController::class, 'projectSammary'])->name('projectSammary');
    Route::get('/project/report/{id}', [ReportController::class, 'projectReport'])->name('project.report');
    

    // Project layer table show
    Route::get('/project-with-layers', [ReportController::class, 'projectWithLayers'])->name('projectWithLayers');
    Route::post('/projects/store', [ReportController::class, 'storeProject'])->name('project.store');
    Route::get('/projects/{id}/edit', [ReportController::class, 'editProject'])->name('project.edit');
    Route::post('/projects/update', [ReportController::class, 'updateProject'])->name('project.update');
    Route::delete('/projects/{id}', [ReportController::class, 'destroyProject'])->name('project.delete');
    Route::post('project/update-dates', [ReportController::class, 'updateDates'])->name('project.updateDates');
    Route::post('/layers/reorder', [ReportController::class, 'reorderLayers'])->name('layers.reorder');
    Route::post('/project/child/store', [ReportController::class, 'storeProjectChild'])->name('project.child.store');
    Route::get('/project/child/edit/{id}', [ReportController::class, 'editProjectChild'])->name('project.child.edit');
    Route::post('/project/child/update', [ReportController::class, 'updateProjectChild'])->name('project.child.update');
    Route::delete('/project/child/delete/{id}', [ReportController::class, 'deleteProjectChild'])->name('project.child.delete');
    Route::post('/project/child/update-dates', [ReportController::class, 'updateDatesAjax'])->name('project.child.updateDates');


    // Sampad Singha
    Route::resource('/layers', LayerController::class)->names('layer');
    Route::patch('/layers/{layer}/status/{status}', [LayerController::class, 'updateStatus'])->name('layer.updateStatus');

    Route::resource('layer-types', LayerTypeController::class)->names('layerType')->except('show');


    Route::any('/layer-list', [LayerController::class, 'layerList'])->name('layerList');
    Route::post('/store-layers', [LayerController::class, 'storeLayer'])->name('storeLayer');
    Route::any('/update-layer-status', [LayerController::class, 'updateLayerStatus'])->name('updateLayerStatus');
    Route::any('/layer-types-store', [LayerController::class, 'updateLayerType'])->name('layer-types.store');
    Route::post('/layers/inline-update', [LayerController::class, 'inlineUpdate'])->name('layers.inlineUpdate');
    Route::any('/layers/update-schedule/{layer}', [LayerController::class, 'updateLayerSchedule'])->name('layers.updateSchedule');
    

    Route::resource('statuses', StatusController::class)->names('status')->except('show');

    Route::get('/board', [LayerController::class, 'board'])->name('board');
    Route::get('/board/data', [LayerController::class, 'boardData'])->name('board.data');
    Route::get('/board/layers', [LayerController::class, 'parentLayers']);
    Route::get('/board/layers/{layer}', [LayerController::class, 'layerDetailJson'])->name('board.layerDetailJson');
    Route::patch('/board/layers/{layer}', [LayerController::class, 'updateLayerJson'])->name('board.layers.updateLayerJson');

});

require __DIR__ . '/auth.php';
