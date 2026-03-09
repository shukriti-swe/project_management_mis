@extends('layouts.backend.app')

@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-10 mx-auto">

                    <h6 class="mb-0 text-uppercase">Hierarchy Management</h6>
                    <hr/>

                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">

                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-layer me-1 font-22 text-danger"></i></div>
                                <h5 class="mb-0 text-danger">Add Layer to Project</h5>
                            </div>

                            <hr>

                            <form class="row g-4" method="POST" action="{{ route('layer.store') }}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="project_id" value="{{ $project->id }}">

                                {{-- Layer Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Layer Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
                                        <input type="text" name="name" class="form-control border-start-0"
                                               placeholder="Phase 1, Module A..." required>
                                    </div>
                                </div>

                                {{-- Layer Type --}}
                                <div class="col-md-3">
                                    <label class="form-label">Layer Type</label>
                                    <select class="form-select" name="type">
                                        <option value="container">Container</option>
                                        <option value="task">Task</option>
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="blocked">Blocked</option>
                                    </select>
                                </div>

                                {{-- Parent Layer --}}
                                <div class="col-md-6">
                                    <label class="form-label">Parent Layer</label>
                                    <select class="form-select" name="parent_id">
                                        <option value="">-- No Parent (Top Level) --</option>

                                        @foreach($parentLayers as $parent)
                                            <option value="{{ $parent->id }}">
                                                {{ str_repeat('— ', $parent->depth) }} {{ $parent->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- Start Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="datetime-local" name="start_time" class="form-control">
                                </div>

                                {{-- End Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">End Time</label>
                                    <input type="datetime-local" name="end_time" class="form-control">
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control editor" name="description" rows="4"></textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-danger px-5">Save Layer</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Back</a>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection