@extends('layouts.backend.app')
@push('css')
    <style>
        body {
            background: #fff;
        }

        /* Table container */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        /* Header */
        .table-modern thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
            padding: 14px 16px;
        }

        /* Body cells */
        .table-modern tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 14px;
            color: #343a40;
            vertical-align: middle;
        }

        /* Row hover */
        .table-modern tbody tr {
            transition: all 0.18s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8fbff;
            box-shadow: inset 0 0 0 1px #e6f0ff;
        }

        /* Last row border cleanup */
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* Table wrapper outline */
        .table-modern-wrapper {
            border: 1px solid #eef1f4;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Status dot */
        .table-modern .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Type circle (T / C) */
        .layer-type-icon {
            width: 22px;
            height: 22px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .layer-type-icon[data-type="task"] {
            background: #0d6efd;
        }

        .layer-type-icon[data-type="container"] {
            background: #f59f00;
        }

        .table-responsive{
            overflow: visible;
        }

        /* Action buttons */
        .table-modern .btn-outline-primary {
            border-color: #dee2e6;
            color: #495057;
        }

        .table-modern .btn-outline-primary:hover {
            background: #f1f5ff;
            border-color: #d0d9ff;
            color: #0d6efd;
        }

        /* subtle row cursor */
        .table-modern tbody tr {
            cursor: pointer;
        }

        /* smoother table spacing on responsive */
        .table-modern td small {
            font-size: 12px;
            color: #6c757d;
        }

        /* 3-dot trigger button */

        .dropdown > button {
            border: none;
            background: transparent;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* hover / focus / active state for trigger */

        .dropdown > button:hover,
        .dropdown > button:focus,
        .dropdown > button:active {
            background-color: #f1f3f5;
            border: none;
            outline: none !important;
            box-shadow: none !important;
        }

        /* remove bootstrap focus outline */

        .dropdown > button:focus-visible {
            outline: none;
        }

        /* dropdown menu item hover */

        .dropdown-menu .dropdown-item:hover,
        .dropdown-menu .dropdown-item:focus,
        .dropdown-menu .dropdown-item:active {
            background-color: #f1f3f5;
            color: inherit;
            outline: none !important;
            box-shadow: none !important;
        }

        /* optional: slightly smoother menu feel */

        .dropdown-menu {
            border-radius: 8px;
            padding: 6px 0;
        }

        /* optional: softer icon alignment */

        .dropdown-item i {
            font-size: 14px;
            vertical-align: middle;
        }

    </style>
@endpush
@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">

            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <div class="row align-items-center">

                                {{-- LEFT : PROJECT TITLE --}}

                                <div class="col-lg-5">

                                    <h2 class="fw-bold mb-1">
                                        {{ $project->title }}
                                    </h2>

                                    <div class="text-muted small">

                                        <span class="me-3">
                                            <i class="bx bx-calendar"></i>
                                            {{ $project->start_date?->format('d M Y') ?? 'N/A' }}
                                            </span>
                                        <span class="me-3"><i class="bx bx-calendar-check"></i>
                                            {{ $project->end_date?->format('d M Y') ?? 'N/A' }}
                                        </span>
                                        <span class="badge bg-secondary">{{ $project->status ?? 'N/A' }}</span>
                                    </div>

                                </div>

                                {{-- CENTER : PROGRESS --}}

                                <div class="col-lg-4">

                                    <div class="small text-muted mb-1">
                                        Project Progress
                                    </div>

                                    <div class="progress" style="height:10px;">
                                        <div class="progress-bar bg-primary"
                                             style="width: {{ $project->progress_percent ?? 0 }}%">
                                        </div>
                                    </div>

                                    <div class="small text-muted mt-1">
                                        {{ $project->progress_percent ?? 0 }}%
                                    </div>

                                </div>

                                {{-- RIGHT : ACTIONS --}}

                                <div class="col-lg-3 text-end">

                                    <a href="{{ route('editProject',$project->id) }}"
                                       class="btn btn-primary btn-sm">
                                        Edit Project </a>

                                </div>

                            </div>

                            <hr class="my-4">

                            @if($project->description)

                                <div class="text-muted">
                                    {!! $project->description !!}
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-10 mx-auto">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div>
                                    <h5 class="fw-bold mb-0">Layers</h5>
                                    <small class="text-muted">Top level structure of this project</small>
                                </div>

                                <a href="{{ route('layer.create',['project'=>$project->id]) }}"
                                   class="btn btn-danger btn-sm">
                                    Add Layer </a>

                            </div>

                            <div class="table-responsive">

                                <table id="layersTable" class="table Xtable-hover align-middle table-modern">

                                    <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Progress</th>
                                        <th>Assigned To</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @foreach($layers as $layer)
                                        <tr onclick="window.location='{{ route('layer.show',$layer->id) }}'"
                                            style="cursor:pointer"
                                            class="layer-row">

                                            <td class="fw-semibold">
                                                <div class="d-flex align-items-center">

                                                    <span
                                                            class="me-2 rounded-circle text-white d-flex justify-content-center align-items-center flex-shrink-0"
                                                            style="
                                                            width:22px;
                                                            height:22px;
                                                            font-size:16px;
                                                            padding-top: 2px;
                                                            background-color: {{ $layer->type === 'task' ? '#0d6efd' : '#e69406' }};
                                                        ">
                                                        {{ $layer->type === 'task' ? 'T' : 'C' }}
                                                    </span>

                                                    {{ $layer->name }}

                                                </div>
                                            </td>

                                            <td>

                                                @if($layer->type === 'task')

                                                    @if($layer->status)
                                                        <div class="d-flex align-items-center gap-2">

                                                            <span
                                                                    style="
                                                                    width:10px;
                                                                    height:10px;
                                                                    border-radius:50%;
                                                                    background-color: {{ $layer->status->color }};
                                                                    display:inline-block;">
                                                            </span>

                                                            <span style="color: {{ $layer->status->color }}; font-weight:500;">
                                                                {{ $layer->status->label }}
                                                            </span>

                                                        </div>
                                                    @else
                                                        <span class="text-muted">No Status</span>
                                                    @endif

                                                @else

                                                    <div class="d-flex align-items-center gap-2">
                                                        <div id="progress-{{ $layer->id }}"
                                                             style="width:32px;height:32px;"></div>
                                                        <small class="text-muted">{{ $layer->progress_percent ?? 0 }}
                                                            %</small>
                                                    </div>

                                                @endif

                                            </td>

                                            <td class="text-muted">
                                                —
                                            </td>

                                            <td>
                                                {{ $layer->start_time?->format('d M Y') ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $layer->end_time?->format('d M Y') ?? '—' }}
                                            </td>

                                            <td class="text-end">

                                                <div class="dropdown" onclick="event.stopPropagation()">

                                                    <button
                                                            class="btn btn-md btn-light bg-transparent border-0"
                                                            type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">

                                                        <i class="bx bx-dots-vertical-rounded"></i>

                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end">

                                                        <li>
                                                            <a class="dropdown-item"
                                                               href="{{ route('layer.edit',$layer->id) }}">
                                                                <i class="bx bx-edit me-1"></i>
                                                                Edit
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('layer.destroy',$layer->id) }}"
                                                                  onsubmit="return confirm('Delete this layer?')">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="bx bx-trash me-1"></i>
                                                                    Delete
                                                                </button>

                                                            </form>
                                                        </li>

                                                    </ul>

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

        </div>
    </div>
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showToast(@json(session('success')), 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showToast(@json(session('error')), 'error');
            });
        </script>
    @endif
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            @foreach($layers as $layer)

            new ProgressBar.Circle('#progress-{{ $layer->id }}', {
                strokeWidth: 16,
                color: '{{ ($layer->progress_percent ?? 0) == 100 ? "#1e965f" : "#0d6efd" }}',
                trailColor: '#c1d5ea',
                trailWidth: 16,
                easing: 'easeInOut',
                duration: 600,
            }).animate({{ ($layer->progress_percent ?? 0) / 100 }});

            @endforeach

        });
    </script>
@endpush
