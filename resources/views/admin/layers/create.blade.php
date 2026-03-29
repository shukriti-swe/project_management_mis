@extends('layouts.backend.app')

@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-10 mx-auto">

                    <h6 class="mb-0 text-uppercase">Create Layer</h6>
                    <hr/>

                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">

                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-layer me-1 font-22"></i></div>
{{--                                <h5 class="mb-0">{{$parent? 'Add Sub Layer to: ' : 'Add Layer to Project: '}}--}}
{{--                                    <span class="text-danger">{{$parent ? $parent->name : $project->title}}</span></h5>--}}
                            </div>

                            <hr>

                            <form class="row g-4" method="POST" action="{{ route('layer.store') }}" enctype="multipart/form-data">
                                @csrf

{{--                                <input type="hidden" name="project_id" value="{{ $project ? $project->id : null }}">--}}
{{--                                <input type="hidden" name="parent_id" value="{{ $parent? $parent->id : null }}">--}}

                                {{-- Layer Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Layer Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
                                        <input
                                                type="text"
                                                name="name"
                                                value="{{old('name')}}"
                                                class="form-control border-start-0"
                                                placeholder="Phase 1, Module A..."
                                                required>
                                    </div>
                                </div>

                                {{-- Layer Type --}}
                                <div class="col-md-3">
                                    <label class="form-label">Layer Type <span class="text-danger">*</span></label>
                                    <select name="layer_type_id" id="layerTypeSelect" class="form-select" required>
                                        <option value="">-- Select or type new --</option>
                                        @foreach($layerTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3">
                                    <div id="status-wrapper" style="display: none">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status_id">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if($project == null)
                                    <div class="col-md-6">
                                        <label class="form-label ">Project <span class="text-danger">*</span></label>
                                        <select name="project_id" class="form-select single-select-no-parent" required>
                                            <option value="">-- Select Project --</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="project_id" value="{{ $project ? $project->id : null }}">
                                @endif

                                @if($parent == null)
                                    <div class="col-md-6">
                                        <label class="form-label">Parent Layer</label>
                                        <select name="parent_id" class="form-select single-select">
                                            <option value="">-- No Parent (This is a top layer) --</option>
                                            @foreach($parentLayers as $l)
                                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="parent_id" value="{{ $parent? $parent->id : null }}">
                                @endif

                                {{-- Start Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="form-control">
                                </div>

                                {{-- End Time --}}
                                <div class="col-md-3">
                                    <label class="form-label">End Time</label>
                                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Assign Users</label>

                                    <select name="users[]" class="form-select user-select" multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control editor" name="description" rows="4">{{ old('description') }}</textarea>
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