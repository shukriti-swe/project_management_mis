@extends('layouts.backend.app')

@push('css')
    <style>

        body {
            background: #fff;
        }

        .page-content {
            background: #fff
        }

        /* SECTION HEADERS */
        .section-title {
            font-size: 18px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 18px;
        }

        /* HEADER BADGES */
        .layer-badges .badge {
            font-size: 11px;
            margin-right: 6px;
        }

        .layer-title {
            font-size: 28px;
            font-family: Roboto, sans-serif !important;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 32px 0;
        }

        .action-group {
            display: flex;
            gap: 12px;
        }

        /* STATUS BUTTON GROUP */

        .status-btn {
            border: 1px solid var(--status-color);
            color: var(--status-color);
            font-size: 12px;
            padding: 6px 10px;
        }

        .status-btn.active {
            background: var(--status-color);
            color: #fff;
        }

        /* HEADER PROGRESS */
        .header-progress {
            display: flex;
            align-items: center;
            gap: 24px;
            justify-content: flex-end;
        }

        .header-progress .progress-text {
            font-size: 13px;
            color: #6c757d;
        }

        #layer-progress {
            width: 64px;
            height: 64px;
        }

        .description {
            margin-top: 32px;
        }

        /* ASSIGNED USERS */
        .assigned-users {
            display: flex;
            gap: 38px;
            flex-wrap: wrap;
            align-items: center;
        }

        .assigned-user {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .assigned-user img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-bottom: 8px;
        }

        .user-name {
            font-size: 15px;
            font-weight: 500;
        }

        .assigned-meta {
            font-size: 13px;
            color: #6c757d;
            margin-top: 2px;
        }

        /* ACTIVITY TIMELINE */

        .event-timeline {
            margin-top: 10px;
        }

        .timeline-event {
            display: grid;
            grid-template-columns:140px 20px 1fr;
            gap: 16px;
            align-items: start;
            margin-bottom: 24px;
            position: relative;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0ea5e9;
            margin-top: 6px;
            position: relative;
        }

        /* vertical line after dot */
        .timeline-dot::after {
            content: "";
            position: absolute;
            top: 18px;
            left: 4px;
            width: 2px;
            height: calc(100% + 24px);
            background: #dee2e6;
        }

        /* remove line on last item */
        .timeline-event:last-child .timeline-dot::after {
            display: none;
        }

        .timeline-time {
            font-size: 13px;
            color: #6c757d;
            text-align: right;
        }

        .timeline-content {
            font-size: 14px;
        }

        .timeline-content span {
            color: #0ea5e9;
        }

        /* TABLE */

        .table-responsive {
            overflow: visible;
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-modern thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
            padding: 14px 16px;
        }

        .table-modern tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: .18s;
            cursor: pointer;
        }

        .table-modern tbody tr:hover {
            background: #f8fbff;
            box-shadow: inset 0 0 0 1px #e6f0ff;
        }

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
            background: #0d6efd
        }

        .layer-type-icon[data-type="container"] {
            background: #e69406
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
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

            {{-- BREADCRUMB --}}
            <div class="row mb-3">
                <div class="col-xl-10 mx-auto">

                    @php
                        $ancestors=$layer->ancestors;
                        $count=$ancestors->count();
                    @endphp

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="{{ route('projectDetails',$layer->project_id) }}">
                                    {{ $layer->project->title }}
                                </a>
                            </li>

                            @if($count<=2)

                                @foreach($ancestors as $ancestor)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('layer.show',$ancestor->id) }}">
                                            {{ $ancestor->name }}
                                        </a>
                                    </li>
                                @endforeach

                            @else
                                <li class="breadcrumb-item">....</li>

                                @foreach($ancestors->slice(-2) as $ancestor)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('layer.show',$ancestor->id) }}">
                                            {{ $ancestor->name }}
                                        </a>
                                    </li>
                                @endforeach

                            @endif

                            <li class="breadcrumb-item active">{{ $layer->name }}</li>

                        </ol>
                    </nav>

                </div>
            </div>


            {{-- HEADER --}}
            <div class="row">
                <div class="col-xl-10 mx-auto">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <div class="row align-items-start">
                                <div class="col-lg-8">

                                    <div class="layer-badges mb-2">

                                        <span class="badge {{ $layer->type==='task'?'bg-primary':'bg-warning text-dark' }}">
                                            {{ ucfirst($layer->type) }}
                                        </span>

                                        @if($layer->type==='task' && $layer->status)
                                            <span class="badge" style="background:{{ $layer->status->color }}">
                                                {{ $layer->status->label }}
                                            </span>
                                        @endif
                                    </div>

                                    <h2 class="mb-2 layer-title">{{ $layer->name }}</h2>
                                </div>

                                <div class="col-lg-4">
                                    <div class="header-progress">
                                        <div class="text-muted font-16">
                                            <span class="">Tasks {{$layer->completed_tasks}} / {{$layer->total_tasks}}</span>
                                        </div>
                                        <div id="layer-progress"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="align-items-start action-bar">
                                <div class="status-group">
                                    @if($layer->type==='task')
                                        <div class="btn-group">
                                            @foreach($statuses as $status)
                                                <a href="#"
                                                   class="btn status-btn change-status {{ $layer->status_id == $status->id ? 'active' : '' }}"
                                                   data-url="{{ route('layer.updateStatus',['layer'=>$layer->id,'status'=>$status->id]) }}"
                                                   style="--status-color: {{ $status->color }}">
                                                    {{ $status->label }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="action-group">
                                    <a href="{{ route('layer.edit',$layer->id) }}" class="btn btn-primary btn-sm">
                                        Edit {{ $layer->type==='task'?'Task':'Layer' }}
                                    </a>
                                    <form action="{{route('layer.destroy',$layer->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $layer->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this {{ $layer->type }}?')">
                                            {{'Delete' . ($layer->type==='task'?' Task':' Layer') }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <hr>

                            <div class="text-muted description">
                                {!! $layer->description ?? 'No description available.' !!}
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            {{-- ASSIGNED USERS --}}
            <div class="row mt-4">
                <div class="col-xl-10 mx-auto">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <div class="section-title">ASSIGNED USERS</div>

                            <div class="assigned-users">

                                @foreach($layer->users as $user)
                                    <div class="assigned-user">
                                        <img src="https://i.pravatar.cc/60?img=12" alt="User Avatar">
                                        <div class="user-name">{{$user->name}}<br>[{{$user->email}}]</div>
                                        <div class="assigned-meta">
                                            <p class="m-0">Assigned by {{$user->pivot->assignedBy->name}}</p>
                                            <p class="m-0">{{ $user->pivot->assigned_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>

                </div>
            </div>


            {{-- ACTIVITY TIMELINE --}}
            <div class="row mt-4">
                <div class="col-xl-10 mx-auto">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <div class="section-title">ACTIVITY TIMELINE</div>

                            <div class="event-timeline">

                                <div class="timeline-event">

                                    <div class="timeline-time">
                                        Jun 25<br>10:14 AM
                                    </div>

                                    <div class="timeline-dot"></div>

                                    <div class="timeline-content">
                                        <span>Sarah Khan</span> changed status to <span>In Progress</span>
                                    </div>

                                </div>


                                <div class="timeline-event">

                                    <div class="timeline-time">
                                        Jun 24<br>03:30 PM
                                    </div>

                                    <div class="timeline-dot"></div>

                                    <div class="timeline-content">
                                        <span>Admin</span> assigned <span>John Miller</span> to this task
                                    </div>

                                </div>


                                <div class="timeline-event">

                                    <div class="timeline-time">
                                        Jun 22<br>11:05 AM
                                    </div>

                                    <div class="timeline-dot"></div>

                                    <div class="timeline-content">
                                        <span>Alex Rahman</span> updated the <span>end time</span>
                                    </div>

                                </div>


                                <div class="timeline-event">

                                    <div class="timeline-time">
                                        Jun 18<br>09:10 AM
                                    </div>

                                    <div class="timeline-dot"></div>

                                    <div class="timeline-content">
                                        <span>Admin</span> created this layer
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>


            {{-- CHILDREN TABLE --}}
            @if($layer->type==='container')

                <div class="row mt-4">
                    <div class="col-xl-10 mx-auto">

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="section-title">CHILDREN</div>

                                    <a href="{{ route('layer.create',['project'=>$layer->project_id, 'parent' => $layer->id]) }}"
                                       class="btn btn-info btn-sm">
                                        Add Child </a>
                                </div>

                                <div class="table-responsive">

                                    <table class="table table-modern align-middle">

                                        <thead>
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

                                        @foreach($layer->children as $child)

                                            <tr onclick="window.location='{{ route('layer.show',$child->id) }}'">

                                                <td class="fw-semibold">

                                                    <div class="d-flex align-items-center">

<span class="me-2 layer-type-icon" data-type="{{ $child->type }}">
{{ $child->type==='task'?'T':'C' }}
</span>

                                                        {{ $child->name }}

                                                    </div>

                                                </td>

                                                <td>

                                                    @if($child->type==='task')

                                                        @if($child->status)

                                                            <div class="d-flex align-items-center gap-2">

                                                                <span class="status-dot"
                                                                      style="background:{{ $child->status->color }}"></span>

                                                                <span style="color:{{ $child->status->color }}">
{{ $child->status->label }}
</span>

                                                            </div>

                                                        @else
                                                            <span class="text-muted">No Status</span>
                                                        @endif

                                                    @else

                                                        <div class="d-flex align-items-center gap-2">
                                                            <div id="progress-{{ $child->id }}"
                                                                 style="width:32px;height:32px;"></div>
                                                            <small class="text-muted">{{ $child->progress_percent ?? 0 }}
                                                                %</small>
                                                        </div>

                                                    @endif

                                                </td>

                                                <td class="text-muted">—</td>

                                                <td>{{ $child->start_time?->format('d M Y') ?? '—' }}</td>

                                                <td>{{ $child->end_time?->format('d M Y') ?? '—' }}</td>

                                                <td class="text-end">

                                                    <div class="dropdown" onclick="event.stopPropagation()">

                                                        <button
                                                                class="btn btn-md btn-light bg-transparent border-0"
                                                                type="button"
                                                                data-bs-toggle="dropdown"
                                                                data-bs-display="static"
                                                                aria-expanded="false">

                                                            <i class="bx bx-dots-vertical-rounded"></i>

                                                        </button>

                                                        <ul class="dropdown-menu dropdown-menu-end">

                                                            <li>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('layer.edit',$child->id) }}">
                                                                    <i class="bx bx-edit me-1"></i>
                                                                    Edit
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <form method="POST"
                                                                      action="{{ route('layer.destroy',$child->id) }}"
                                                                      onsubmit="return confirm('Delete this layer?')">

                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit"
                                                                            class="dropdown-item text-danger">
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

            @endif

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

            new ProgressBar.Circle('#layer-progress', {
                strokeWidth: 10,
                trailWidth: 10,
                color: '{{ ($layer->progress_percent ?? 0) == 100 ? "#1e965f" : "#0d6efd" }}',
                trailColor: '#e5e7eb',
                duration: 600,
                text: {
                    value: '{{ $layer->progress_percent ?? 0 }}%',
                    style: {
                        color: '#495057',
                        position: 'absolute',
                        left: '50%',
                        top: '50%',
                        padding: 0,
                        margin: 0,
                        transform: {
                            prefix: true,
                            value: 'translate(-50%, -50%)'
                        },
                        fontSize: '14px',
                        fontWeight: 600
                    }
                }

            }).animate({{ ($layer->progress_percent ?? 0)/100 }});

            @foreach($layer->children as $child)

            @if($child->type==='container')

            new ProgressBar.Circle('#progress-{{ $child->id }}', {
                strokeWidth: 16,
                color: '{{ ($layer->progress_percent ?? 0) == 100 ? "#1e965f" : "#0d6efd" }}',
                trailColor: '#e5e7eb',
                trailWidth: 16,
                duration: 600
            }).animate({{ ($child->progress_percent ?? 0)/100 }});

            @endif

            @endforeach

            document.querySelectorAll('.change-status').forEach(btn => {

                btn.addEventListener('click', function (e) {

                    e.preventDefault();

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.dataset.url;

                    // CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    // PATCH method
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PATCH';

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();

                });

            });

        });
    </script>
@endpush