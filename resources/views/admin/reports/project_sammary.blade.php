@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Projects</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Project Directory</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter by Status:</label>
                        <select id="projectStatusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="In-Active">In-Active</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter by User:</label>
                        <select id="projectUserFilter" class="form-select">
                            <option value="">All User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="projectListTable" class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Project Name</th>
                                <th>Manager</th>
                                <th>Type</th>
                                <th>Layers</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>#{{ $project->id }}</td>
                                <td class="fw-bold text-primary">{{ $project->title }}</td>
                                <td>{{ $project->manager->name ?? 'N/A' }}</td>
                                <td>{{ $project->type ?? 'General' }}</td>
                                <td>
                                    <span class="badge bg-light-info text-info px-3">
                                        <i class="bx bx-layer"></i> {{ $project->layers_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $project->status == 'Active' ? 'success' : 'secondary' }}">
                                        {{ $project->status->label ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('project.report', $project->id) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center" title="View Report">
                                            <i class="bx bx-file-find me-1"></i> Report
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#projectListTable').DataTable({
            lengthChange: true,
            order: [
                [0, 'desc']
            ]
        });

        $('#projectStatusFilter').on('change', function() {
            var val = $(this).val();
            table.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        $('#projectUserFilter').on('change', function() {
            var val = $(this).val();
            table.column(2).search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
</script>

@endsection