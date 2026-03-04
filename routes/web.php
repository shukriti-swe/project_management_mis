<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::post('/ckeditor-upload', [ProfileController::class, 'ckeditorUpload'])->name('ckeditor.upload');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::any('/project-list', [AdminController::class, 'projectList'])->name('projectList');
    Route::any('/add-project', [App\Http\Controllers\AdminController::class, 'addProject'])->name('addProject');
    Route::any('/save-project', [App\Http\Controllers\AdminController::class, 'saveProject'])->name('saveProject');
    Route::any('/edit-project/{id}', [App\Http\Controllers\AdminController::class, 'editProject'])->name('editProject');
    Route::any('/update-project', [App\Http\Controllers\AdminController::class, 'updateProject'])->name('updateProject');
    Route::any('/delete-project/{id}', [App\Http\Controllers\AdminController::class, 'deleteProject'])->name('deleteProject');
});

require __DIR__.'/auth.php';
