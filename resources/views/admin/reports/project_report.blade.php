@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('projectSammary') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Project Analysis</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <button onclick="window.print()" class="btn btn-dark btn-sm px-3"><i class="bx bx-printer mr-1"></i> Print Report</button>
            </div>
        </div>

        <div class="card radius-10 border-start border-0 border-3 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Project Title</p>
                        <h4 class="my-1 text-info">{{ $project->title }}</h4>
                        <p class="mb-0 font-13">Project ID: #{{ $project->id }} | Type: {{ $project->type ?? 'Standard' }}</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <i class='bx bxs-briefcase'></i>
                    </div>
                </div>
                <hr>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <div class="col">
                        <div class="p-2 border rounded text-center bg-light">
                            <h5 class="mb-0">{{ $project->layers->count() }}</h5>
                            <small class="text-muted">Total Layers</small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 border rounded text-center bg-light">
                            <h5 class="mb-0 text-success">
                                {{ $project->layers->filter(function ($layer) {
                                    return $layer->status
                                        && !in_array($layer->status->category, ['done', 'canceled']);
                                })->count() }}
                            </h5>
                            <small class="text-muted">Active Layers</small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 border rounded text-center bg-light">
                            <h5 class="mb-0">{{ date('d M, Y') }}</h5>
                            <small class="text-muted">Report Date</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0 text-uppercase">Execution Timeline & Layer Status</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Layer Name</th>
                                <th>Assigned Users</th>
                                <th>Time Schedule</th>
                                <th>Status</th>
                                <th width="200">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->layers as $layer)
                            <tr>
                                <td class="fw-bold">{{ $layer->name }}</td>
                                <td>
                                    @forelse($layer->users as $user)
                                        <span class="badge bg-light-info text-info border border-info px-2 py-1 mb-1">{{ $user->name }}</span>
                                    @empty
                                        <span class="text-muted small">No user assigned</span>
                                    @endforelse
                                </td>
                                <td>
                                    <div class="small">
                                        <i class='bx bx-calendar-check text-success'></i> {{ $layer->start_time ? date('d M, Y', strtotime($layer->start_time)) : 'N/A' }} <br>
                                        <i class='bx bx-calendar-x text-danger'></i> {{ $layer->end_time ? date('d M, Y', strtotime($layer->end_time)) : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusLabel = $layer->status->label ?? ($layer->status_id == 1 ? 'Active' : 'In-Active');
                                        $badgeClass = ($statusLabel == 'Active') ? 'success' : 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{$layer->progress_percent}}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Updated: {{ $layer->updated_at->format('d-m-Y') }}</small>
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


<style>
    @media print {
        .topbar, .sidebar-wrapper, .btn, .page-breadcrumb { display: none !important; }
        .page-wrapper { margin-left: 0 !important; margin-top: 0 !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    }
</style>

@endsection

