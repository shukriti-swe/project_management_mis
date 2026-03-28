@extends('layouts.backend.app')

@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <h6 class="mb-0 text-uppercase">Status Management</h6>
                    <hr/>

                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">

                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-edit me-1 font-22"></i></div>
                                <h5 class="mb-0">
                                    Edit Status
                                </h5>
                            </div>

                            <hr>

                            <form class="row g-4" method="POST" action="{{ route('status.update', $status->id) }}">
                                @csrf
                                @method('PUT')

                                {{-- Status Label --}}
                                <div class="col-md-6">
                                    <label class="form-label">Status Label</label>

                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <i class='bx bx-tag'></i>
                                        </span>

                                        <input
                                                type="text"
                                                name="label"
                                                value="{{ old('label', $status->label) }}"
                                                class="form-control border-start-0"
                                                required>
                                    </div>
                                </div>

                                {{-- Category --}}
                                <div class="col-md-3">
                                    <div>
                                        <label class="form-label">Category</label>

                                        <select class="form-select" name="category">
                                            <option value="backlog" {{ old('category', $status->category) == "backlog" ? 'selected' : '' }}>Backlog</option>
                                            <option value="todo" {{ old('category', $status->category) == "todo" ? 'selected' : '' }}>Todo</option>
                                            <option value="in_progress" {{ old('category', $status->category) == "in_progress" ? 'selected' : '' }}>In progress</option>
                                            <option value="done" {{ old('category', $status->category) == "done" ? 'selected' : '' }}>Done</option>
                                            <option value="canceled" {{ old('category', $status->category) == "canceled" ? 'selected' : '' }}>Canceled</option>

                                        </select>
                                    </div>
                                </div>

                                {{-- Project --}}
                                <!-- <div class="col-md-6">
                                    <label class="form-label">Project</label>

                                    <select class="form-select project-select" name="project_id">
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}"
                                                    {{ old('project_id', $status->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ ucfirst($project->title) }}
                                            </option>

                                        @endforeach

                                    </select>
                                </div> -->

                                {{-- Buttons --}}
                                <div class="col-12 mt-3">

                                    <button type="submit" class="btn btn-primary px-5">
                                        Update Status
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
        $(document).ready(function () {
            $('.project-select').select2({
                placeholder: "Select an option",
                width: '100%',
                allowClear: true
            });
        });
    </script>
@endpush
