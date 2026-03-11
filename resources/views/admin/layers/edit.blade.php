@extends('layouts.backend.app')

@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <h6 class="mb-0 text-uppercase">Hierarchy Management</h6>
                    <hr/>

                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">

                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-edit me-1 font-22"></i></div>
                                <h5 class="mb-0">
                                    Edit Layer:
                                    <span class="text-primary">{{ $layer->name }}</span>
                                </h5>
                            </div>

                            <hr>

                            <form class="row g-4"
                                  method="POST"
                                  action="{{ route('layer.update', $layer->id) }}">

                                @csrf
                                @method('PUT')

                                <input type="hidden"
                                       name="project_id"
                                       value="{{ old('project_id', $layer->project_id) }}">

                                <input type="hidden"
                                       name="parent_id"
                                       value="{{ old('parent_id', $layer->parent_id) }}">

                                {{-- Layer Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Layer Name</label>

                                    <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <i class='bx bx-tag'></i>
                                </span>

                                        <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $layer->name) }}"
                                                class="form-control border-start-0"
                                                required>
                                    </div>
                                </div>

                                {{-- Layer Type --}}
                                <div class="col-md-3">
                                    <label class="form-label">Layer Type</label>

                                    <select class="form-select" name="type" id="layer-type">

                                        <option value="container"
                                                {{ old('type', $layer->type) == 'container' ? 'selected' : '' }}>
                                            Container
                                        </option>

                                        <option value="task"
                                                {{ old('type', $layer->type) == 'task' ? 'selected' : '' }}>
                                            Task
                                        </option>

                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3">
                                    <div id="status-wrapper" style="{{ old('type', $layer->type ?? 'container') === 'task' ? '' : 'display:none;' }}">
                                        <label class="form-label">Status</label>

                                        <select class="form-select" name="status_id">

                                            @foreach($statuses as $status)

                                                <option value="{{ $status->id }}"
                                                        {{ old('status_id', $layer->status_id) == $status->id ? 'selected' : '' }}>
                                                    {{ $status->label }}
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                {{-- Start Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">Start Time</label>

                                    <input
                                            type="datetime-local"
                                            name="start_time"
                                            value="{{ old('start_time', optional($layer->start_time)->format('Y-m-d\TH:i')) }}"
                                            class="form-control">
                                </div>

                                {{-- End Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">End Time</label>

                                    <input
                                            type="datetime-local"
                                            name="end_time"
                                            value="{{ old('end_time', optional($layer->end_time)->format('Y-m-d\TH:i')) }}"
                                            class="form-control">
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Assign Users</label>

                                    <select name="users[]" class="form-select user-select" multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                    {{ $layer->users->contains($user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control editor" name="description" rows="4">{{ old('description', $layer->description) }}</textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-12 mt-3">

                                    <button type="submit" class="btn btn-primary px-5">
                                        Update Layer
                                    </button>

                                    <a href="{{ url()->previous() }}"
                                       class="btn btn-outline-secondary px-4">
                                        Back
                                    </a>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const typeSelect = document.getElementById('layer-type');
            const statusWrapper = document.getElementById('status-wrapper');

            function toggleStatus() {
                if (typeSelect.value === 'task') {
                    statusWrapper.style.display = 'block';
                } else {
                    statusWrapper.style.display = 'none';
                }
            }

            toggleStatus(); // initial render
            typeSelect.addEventListener('change', toggleStatus);
        });

        $(document).ready(function () {
            $('.user-select').select2({
                placeholder: "Search users",
                width: '100%'
            });
        });
    </script>
@endpush
