@extends('layouts.backend.app')

@section('admin_content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* ════ RESET ════ */
        .dd { max-width: 100% !important; width: 100% !important; }
        .dd-list { width: 100% !important; margin: 0; padding: 0; }

        /* ════ HEADER ════ */
        .table-header {
            display: table !important;
            table-layout: fixed !important;
            width: 100% !important;
            height: 42px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #0f172a;
            border-radius: 8px 8px 0 0;
        }
        .table-header .t-col {
            display: table-cell !important;
            vertical-align: middle !important;
            padding: 0 12px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #ffffff;
            white-space: nowrap;
        }

        /* ════ ROW ════ */
        .dd-handle {
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
            height: 46px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            box-sizing: border-box;
            transition: background 0.12s;
        }
        .dd-handle:hover { background: #f8fafc !important; }

        .dd-handle .t-col {
            display: flex !important;
            align-items: center !important;
            padding: 0 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex-shrink: 0;
            box-sizing: border-box;
            font-size: 13px;
            color: #334155;
        }

        /* ════ COLUMN SIZES ════ */
        .table-header .c-drag   { width: 35px; }
        .table-header .c-toggle { width: 30px; }
        .table-header .c-name   { width: auto; padding-left: 8px !important; }
        .table-header .c-start  { width: 110px; text-align: center; }
        .table-header .c-end    { width: 110px; text-align: center; }
        .table-header .c-status { width: 100px; text-align: center; }
        .table-header .c-users  { width: 180px; }
        .table-header .c-action { width: 110px; text-align: right; padding-right: 16px !important; }

        .dd-handle .c-drag   { width: 35px;  min-width: 35px;  justify-content: center; }
        .dd-handle .c-toggle { width: 30px;  min-width: 30px; }
        .dd-handle .c-name   { flex: 1 1 auto; min-width: 60px; }
        .dd-handle .c-start  { width: 110px; min-width: 110px; justify-content: center; font-size: 11px; color: #64748b; }
        .dd-handle .c-end    { width: 110px; min-width: 110px; justify-content: center; font-size: 11px; color: #64748b; }
        .dd-handle .c-status { width: 100px; min-width: 100px; justify-content: center; }
        .dd-handle .c-users  { width: 180px; min-width: 180px; overflow: hidden; position: relative; cursor: default; }
        .dd-handle .c-action { width: 110px; min-width: 110px; justify-content: flex-end; padding-right: 16px !important; position: relative; z-index: 10; }

        /* ════ PROJECT ROW ════ */
        .dd-item.dd-nodrag > .dd-handle {
            background: #f8faff !important;
            border-left: 3px solid #3b82f6 !important;
            border-bottom: 1px solid #e8f0fe !important;
        }
        .dd-item.dd-nodrag > .dd-handle:hover { background: #f0f6ff !important; }

        /* ════ OVERDUE ROW ════ */
        .dd-handle.overdue,
        .dd-item .dd-handle[style*="dc3545"],
        .dd-item .dd-handle[style*="ef4444"] {
            background: #fff8f8 !important;
        }

        /* ════ DRAG HANDLE ════ */
        .drag-handle {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            cursor: grab;
            opacity: 0;
            transition: opacity 0.15s;
        }
        .dd-handle:hover .drag-handle { opacity: 1; }
        .drag-handle i { font-size: 17px !important; color: #94a3b8 !important; pointer-events: none; }

        /* ════ EXPAND BUTTON ════ */
        .dd-item > button {
            position: absolute !important;
            left: 45px !important;
            top: 5px !important;
            z-index: 11;
            height: 25px !important;
            font-size: 24px !important;
            font-family: Arial, sans-serif !important;
            font-weight: 300 !important;
            color: #94a3b8 !important;
            background: none !important;
            border: none !important;
            transition: color 0.15s;
        }
        .dd-item > button:focus { outline: none !important; }
        .dd-item > button:hover { color: #3b82f6 !important; }

        /* ════ USER TAG ════ */
        .user-tag {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 500;
            margin: 1px;
            display: inline-block;
        }

        /* ════ USER MORE COUNT ════ */
        .user-more-count {
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            margin-left: 3px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 1px 6px;
            border-radius: 20px;
        }
        .dd-handle .c-users.has-tip { cursor: default; }

        /* ════ ACTION ICONS ════ */
        .c-action a {
            font-size: 15px;
            color: #94a3b8;
            opacity: 1;
            transition: color 0.15s, transform 0.15s;
            display: inline-block;
        }
        .c-action a:hover { transform: scale(1.2); }
        .c-action a.text-success:hover { color: #16a34a !important; }
        .c-action a.text-primary:hover { color: #2563eb !important; }
        .c-action a.text-danger:hover  { color: #dc2626 !important; }
        .c-action a.text-info:hover    { color: #0891b2 !important; }
        .c-action a.text-muted:hover   { color: #475569 !important; }

        /* ════ DATE PICKER ════ */
        .date-picker-btn { cursor: pointer; font-size: 15px; transition: 0.2s; }
        .date-picker-btn:hover { transform: scale(1.1); }
        .daterangepicker td.active,
        .daterangepicker td.active:hover,
        .daterangepicker td.in-range {
            background-color: #3b82f6 !important;
            border-color: transparent !important;
            color: #fff !important;
            border-radius: 0 !important;
        }
        .daterangepicker td.start-date { border-radius: 6px 0 0 6px !important; }
        .daterangepicker td.end-date   { border-radius: 0 6px 6px 0 !important; }
        .daterangepicker td.available:hover,
        .daterangepicker th.available:hover { background-color: #2563eb !important; color: #fff !important; }
        .daterangepicker {
            z-index: 9999 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important;
            border: 1px solid #e2e8f0 !important;
        }
        .daterangepicker .btn-primary {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            border-radius: 6px !important;
        }

        /* ════ WRAPPER ════ */
        #nestable { visibility: hidden; }
        .pm-wrap {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        /* ════ FILTER BAR ════ */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .filter-bar .form-control,
        .filter-bar .form-select {
            border-radius: 7px;
            font-size: 13px;
            border-color: #e2e8f0;
            height: 34px;
            box-shadow: none;
        }
        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        /* ════ FLOATING TOOLTIP ════ */
        #user-float-tip {
            position: fixed;
            background: #1e293b;
            color: #f1f5f9;
            font-size: 11px;
            padding: 7px 12px;
            border-radius: 8px;
            white-space: nowrap;
            z-index: 99999;
            pointer-events: none;
            display: none;
            line-height: 1.9;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
        }
    </style>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="card border-0 shadow-none">
                <div class="card-body">

                    <div class="filter-bar">
                        <input type="text" id="treeSearch" class="form-control form-control-sm"
                               style="max-width:200px;" placeholder="🔍 Search...">
                        <select id="filterUser" class="form-control form-control-sm" style="max-width:170px;">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <select id="filterStatus" class="form-control form-control-sm" style="max-width:170px;">
                            <option value="">All Status</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}">{{ $s->label }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm px-3 ms-auto" data-bs-toggle="modal"
                                data-bs-target="#projectModal"
                                onclick="$('#projectForm')[0].reset(); $('#edit_p_id').val('');">
                            <i class="bx bx-plus me-1"></i> New Project
                        </button>
                    </div>

                    <div class="pm-wrap">
                        {{-- Table Header --}}
                        <div class="table-header d-none d-lg-table">
                            <div class="t-col c-drag"></div>
                            <div class="t-col c-toggle"></div>
                            <div class="t-col c-name" style="padding-left: 15px !important;">Project / Layer Name</div>
                            <div class="t-col c-start">Start Date</div>
                            <div class="t-col c-end">End Date</div>
                            <div class="t-col c-status">Status</div>
                            <div class="t-col c-users">Assigned To</div>
                            <div class="t-col c-action">Action</div>
                        </div>

                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                @foreach($projects as $project)
                                    <li class="dd-item dd-nodrag" data-id="p{{ $project->id }}">
                                        <div class="dd-handle">
                                            <div class="t-col c-drag">
                                                <span class="drag-handle">
                                                    <i class="bx bx-grid-vertical"></i>
                                                </span>
                                            </div>
                                            <div class="t-col c-toggle"></div>
                                            <div class="t-col c-name text-primary fw-bold"
                                                 style="padding-left: 15px !important;">
                                                <i class='bx bxs-folder-open me-1'></i> {{ $project->title }}
                                            </div>
                                            <div class="t-col c-start">
                                                <span class="start-txt-p{{ $project->id }}" style="font-size:11px; color:#64748b;">
                                                    {{ $project->start_date ? date('d M, Y', strtotime($project->start_date)) : '---' }}
                                                </span>
                                            </div>
                                            <div class="t-col c-end">
                                                <span class="end-txt-p{{ $project->id }}" style="font-size:11px; color:#64748b;">
                                                    {{ $project->start_date ? date('d M, Y', strtotime($project->end_date)) : '---' }}
                                                </span>
                                            </div>
                                            <div class="t-col c-status">
                                                @if($project->status)
                                                    <span class="badge rounded-pill"
                                                          style="background:{{ $project->status->color ?? '#6c757d' }}; font-size:9px;">
                                                        {{ $project->status->label }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="t-col c-users">
                                                @if($project->user)
                                                    <span class="user-tag" style="background:#e0f2fe; color:#0369a1; border-color:#7dd3fc;">
                                                        {{ $project->user->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="t-col c-action">
                                                <a href="javascript:;" class="date-picker-btn text-info"
                                                   data-id="p{{ $project->id }}" title="Set Date">
                                                    <i class="bx bx-calendar date-picker-btn" data-id="p{{ $project->id }}"></i>
                                                </a>
                                                <a href="javascript:;" class="open-add-modal text-success ms-1"
                                                   data-project="{{ $project->id }}" data-parent=""
                                                   title="Add Layer">
                                                    <i class="bx bx-plus-circle"></i>
                                                </a>
                                                <a href="javascript:;" class="edit-project ms-1 text-primary"
                                                   data-id="{{ $project->id }}" title="Edit Project">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <a href="javascript:;" class="delete-project ms-1 text-danger"
                                                   data-id="{{ $project->id }}"
                                                   data-has-child="{{ $project->layers->count() > 0 ? 'yes' : 'no' }}"
                                                   title="Delete Project">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </div>
                                        </div>

                                        @php
                                            $renderLayers = function($layers, $projectId) use (&$renderLayers) {
                                                echo '<ol class="dd-list">';
                                                foreach ($layers->sortBy('position') as $layer) {
                                                    $s_color      = $layer->status->color ?? '#6c757d';
                                                    $hasChildAttr = ($layer->children->count() > 0) ? 'yes' : 'no';

                                                    // ── User tags with tooltip ──
                                                    $all_users  = $layer->users;
                                                    $all_names  = $all_users->map(fn($u) => htmlspecialchars($u->name))->implode('|');
                                                    $first      = $all_users->first();
                                                    $extra      = $all_users->count() - 1;
                                                    $u_tags     = $first ? "<span class='user-tag'>" . htmlspecialchars($first->name) . "</span>" : '';
                                                    if ($extra > 0) $u_tags .= "<span class='user-more-count'>+$extra</span>";

                                                    $style = ($layer->end_time < now() && $layer->status->category != "done")
                                                        ? 'background:#fff5f5 !important; border-left: 3px solid #ef4444 !important;'
                                                        : '';

                                                    echo '
                                                    <li class="dd-item" data-id="'.$layer->id.'"
                                                        data-users="'.implode(',', $layer->users->pluck('id')->toArray()).'"
                                                        data-status="'.$layer->status_id.'">
                                                        <div class="dd-handle" style="'.$style.'">
                                                            <div class="t-col c-drag">
                                                                <span class="drag-handle"><i class="bx bx-grid-vertical"></i></span>
                                                            </div>
                                                            <div class="t-col c-toggle"></div>
                                                            <div class="t-col c-name">
                                                                <i class="bx bx-hash text-muted" style="font-size:12px;"></i>
                                                                <span class="ms-1" style="font-size:13px;">'.$layer->name.'</span>
                                                            </div>
                                                            <div class="t-col c-start">
                                                                <span class="start-txt-'.$layer->id.'" style="font-size:11px; color:#475569;">'.($layer->start_time ? date('d M, Y H:i', strtotime($layer->start_time)) : '---').'</span>
                                                            </div>
                                                            <div class="t-col c-end">
                                                                <span class="end-txt-'.$layer->id.'" style="font-size:11px; color:#475569;">'.($layer->end_time ? date('d M, Y H:i', strtotime($layer->end_time)) : '---').'</span>
                                                            </div>
                                                            <div class="t-col c-status">
                                                                <span class="badge rounded-pill" style="background:'.$s_color.'; font-size:9px; letter-spacing:0.3px;">'.($layer->status->label ?? 'N/A').'</span>
                                                            </div>
                                                            <div class="t-col c-users has-tip" data-names="'.$all_names.'">'.$u_tags.'</div>
                                                            <div class="t-col c-action">
                                                                <a href="javascript:;" class="date-picker-btn text-info" data-id="'.$layer->id.'" title="Set Date">
                                                                    <i class="bx bx-calendar date-picker-btn" data-id="'.$layer->id.'"></i>
                                                                </a>
                                                                <a href="javascript:;" class="open-add-modal text-success ms-1" data-project="'.$projectId.'" data-parent="'.$layer->id.'" title="Add Child Layer">
                                                                    <i class="bx bx-plus-circle"></i>
                                                                </a>
                                                                <a href="javascript:;" class="edit-layer ms-1 text-muted" data-id="'.$layer->id.'" title="Edit Layer">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </a>
                                                                <a href="javascript:;" class="delete-layer ms-1 text-danger" data-id="'.$layer->id.'" data-has-child="'.$hasChildAttr.'" title="Delete Layer">
                                                                    <i class="bx bx-trash"></i>
                                                                </a>
                                                            </div>
                                                        </div>';

                                                    if ($layer->children->count() > 0) {
                                                        $renderLayers($layer->children, $projectId);
                                                    }

                                                    echo '</li>';
                                                }
                                                echo '</ol>';
                                            };
                                        @endphp
                                        {!! $renderLayers($project->layers, $project->id) !!}
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>{{-- end pm-wrap --}}

                </div>
            </div>
        </div>
    </div>

    {{-- Floating tooltip div --}}
    <div id="user-float-tip"></div>

    {{-- Layer Modal --}}
    <div class="modal fade" id="layerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="layerForm">@csrf
                    <input type="hidden" name="project_id" id="modal_project_id">
                    <input type="hidden" name="parent_id" id="modal_parent_id">
                    <input type="hidden" name="layer_id" id="modal_layer_id">
                    <div class="modal-header bg-dark text-white py-2">
                        <h6 class="modal-title">Layer Setup</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="fw-bold small">Layer Name</label>
                            <input type="text" name="name" id="modal_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small">Assign Users</label>
                            <select name="user_ids[]" id="layer_users" class="form-control select2-multiple" multiple="multiple">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="small fw-bold">Status</label>
                                <select name="status_id" id="modal_status_id" class="form-select">
                                    @foreach($statuses as $s)
                                        <option value="{{ $s->id }}">{{ $s->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small fw-bold">Start Date</label>
                                <input type="date" name="start_time" id="modal_start_time" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="small fw-bold">End Date</label>
                                <input type="date" name="end_time" id="modal_end_time" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Project Modal --}}
    <div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="projectForm">@csrf
                    <input type="hidden" name="project_id" id="edit_p_id">
                    <div class="modal-header bg-primary text-white py-2">
                        <h6 class="modal-title">New Project</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="fw-bold small">Title</label>
                            <input type="text" name="title" id="p_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small">Manager</label>
                            <select name="user_id" id="p_user_id" class="form-select" required>
                                <option value="">Select Manager</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small">Status</label>
                            <select name="status_id" id="p_status_id" class="form-select">
                                @foreach($statuses as $s)
                                    <option value="{{ $s->id }}">{{ $s->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="p_save_btn">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            // ১. Nestable Init
            $('#nestable').nestable({
                maxDepth: 10,
                handleClass: 'drag-handle',
                callback: function (l, e) {
                    let item = e.length ? $(e[0]) : null;
                    if (!item) return;

                    let originalProject = item.closest('.dd-item.dd-nodrag').data('id');
                    let newProject = item.parent().closest('.dd-item.dd-nodrag').data('id');

                    if (originalProject !== newProject) {
                        Swal.fire('Not allowed', 'Cannot move layer to another project', 'warning');
                        location.reload();
                        return;
                    }

                    $.post("{{ route('layers.reorder') }}", {
                        _token: "{{ csrf_token() }}",
                        hierarchy: $('#nestable').nestable('serialize')
                    });
                }
            });

            $('#nestable').nestable('collapseAll');
            $('#nestable').css('visibility', 'visible');

            // ২. Filter
            $('#filterUser, #filterStatus').on('change', function () {
                applyFilters();
            });

            function applyFilters() {
                let userId   = $('#filterUser').val();
                let statusId = $('#filterStatus').val();

                if (!userId && !statusId) {
                    $('#nestable .dd-item').show();
                    $('#nestable').nestable('collapseAll');
                    return;
                }

                $('#nestable').nestable('collapseAll');
                $('#nestable .dd-item').hide();

                $('#nestable .dd-item').each(function () {
                    let item = $(this);
                    if (item.hasClass('dd-nodrag')) return;

                    let users  = item.data('users') ? item.data('users').toString().split(',') : [];
                    let status = item.data('status') ? item.data('status').toString() : '';

                    let userMatch   = !userId   || users.includes(userId);
                    let statusMatch = !statusId || status === statusId;

                    if (userMatch && statusMatch) {
                        item.show();
                        item.parents('.dd-item').each(function () {
                            $(this).show();
                            let btn = $(this).children('button[data-action="expand"]');
                            if (btn.length) btn.click();
                        });
                    }
                });

                $('#nestable > .dd-list > .dd-item').each(function () {
                    let project = $(this);
                    if (project.find('.dd-item:visible').length > 0) {
                        project.show();
                    }
                });
            }

            // ৩. Select2
            $('.select2-multiple').select2({ width: '100%', dropdownParent: $('#layerModal') });

            // ৪. Add Layer Modal
            $(document).on('click', '.open-add-modal', function () {
                $('#layerForm')[0].reset();
                $('#modal_layer_id').val('');
                $('#modal_project_id').val($(this).attr('data-project'));
                $('#modal_parent_id').val($(this).attr('data-parent'));
                $('#modal_name').val('');
                $('#modal_start_time').val('');
                $('#modal_end_time').val('');
                $('#layer_users').val(null).trigger('change');
                $('#layerModal .modal-title').text('New Layer Setup');
                $('#layerModal').modal('show');
            });

            // ৫. Edit Layer
            $(document).on('click', '.edit-layer', function () {
                let id      = $(this).attr('data-id');
                let editUrl = "{{ route('project.child.edit', ':id') }}".replace(':id', id);
                $('#layerForm')[0].reset();
                $.get(editUrl, function (data) {
                    $('#modal_layer_id').val(data.id);
                    $('#modal_project_id').val(data.project_id);
                    $('#modal_parent_id').val(data.parent_id);
                    $('#modal_name').val(data.name);
                    $('#modal_status_id').val(data.status_id);

                    function formatDate(dateStr) {
                        if (!dateStr) return '';
                        return new Date(dateStr).toISOString().split('T')[0];
                    }

                    $('#modal_start_time').val(formatDate(data.start_time));
                    $('#modal_end_time').val(formatDate(data.end_time));

                    if (data.users) {
                        $('#layer_users').val(data.users.map(u => u.id)).trigger('change');
                    }
                    $('#layerModal .modal-title').text('Edit Layer Setup');
                    $('#layerModal').modal('show');
                });
            });

            // ৬. Edit Project
            $(document).on('click', '.edit-project', function () {
                let id  = $(this).attr('data-id');
                let url = "{{ route('project.edit', ':id') }}".replace(':id', id);
                $.get(url, function (data) {
                    $('#edit_p_id').val(data.id);
                    $('#p_title').val(data.title);
                    $('#p_user_id').val(data.user_id);
                    $('#p_status_id').val(data.status_id);
                    $('#projectModal .modal-title').text('Edit Project');
                    $('#p_save_btn').text('Update Project');
                    $('#projectModal').modal('show');
                });
            });

            // ৭. Delete Layer
            $(document).on('click', '.delete-layer', function () {
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('project.child.delete', ':id') }}".replace(':id', id);
                        $.post(url, { _token: "{{ csrf_token() }}", _method: 'DELETE' }, function (res) {
                            if (res.status === 'success') location.reload();
                        });
                    }
                });
            });

            // ৮. Delete Project
            $(document).on('click', '.delete-project', function () {
                let id = $(this).attr('data-id');
                if ($(this).attr('data-has-child') === 'yes') {
                    Swal.fire('Access Denied!', 'Please delete all layers under this project first.', 'error');
                    return;
                }
                Swal.fire({
                    title: 'Delete Project?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let url = "{{ route('project.delete', ':id') }}".replace(':id', id);
                        $.post(url, { _token: "{{ csrf_token() }}", _method: 'DELETE' }, function (res) {
                            if (res.status === 'success') location.reload();
                        });
                    }
                });
            });

            // ৯. Date Range Picker
            $(document).on('click', '.date-picker-btn', function (e) {
                e.stopPropagation();
                let el = $(this);
                let id = el.attr('data-id');
                if (!el.data('daterangepicker')) {
                    let currentStart = $('.start-txt-' + id).text().trim();
                    let currentEnd   = $('.end-txt-' + id).text().trim();
                    let startVal     = (currentStart !== '---') ? moment(currentStart, 'DD MMM, YYYY') : moment();
                    let endVal       = (currentEnd   !== '---') ? moment(currentEnd,   'DD MMM, YYYY') : moment();

                    el.daterangepicker({
                        startDate: startVal,
                        endDate: endVal,
                        opens: 'left',
                        timePicker: true,
                        timePicker24Hour: true,
                        locale: { format: 'YYYY-MM-DD HH:mm' }
                    });
                    el.data('daterangepicker').show();
                    el.on('apply.daterangepicker', function (ev, picker) {
                        let isProject = String(id).startsWith('p');
                        if (isProject) {
                            let projectId = String(id).replace('p', '');
                            $.post("{{ route('project.updateDates') }}", {
                                _token:     "{{ csrf_token() }}",
                                project_id: projectId,
                                start_time: picker.startDate.format('YYYY-MM-DD HH:mm:ss'),
                                end_time:   picker.endDate.format('YYYY-MM-DD HH:mm:ss')
                            }, function () { location.reload(); });
                        } else {
                            $.post("{{ route('project.child.updateDates') }}", {
                                _token:     "{{ csrf_token() }}",
                                layer_id:   id,
                                start_time: picker.startDate.format('YYYY-MM-DD HH:mm:ss'),
                                end_time:   picker.endDate.format('YYYY-MM-DD HH:mm:ss')
                            }, function () { location.reload(); });
                        }
                    });
                } else {
                    el.data('daterangepicker').show();
                }
            });

            // ১০. Form Submit
            $('#layerForm, #projectForm').on('submit', function (e) {
                e.preventDefault();
                let form      = $(this);
                let isProject = (form.attr('id') === 'projectForm');
                let editId    = isProject ? $('#edit_p_id').val() : $('#modal_layer_id').val();

                let url = isProject
                    ? (editId ? "{{ route('project.update') }}" : "{{ route('project.store') }}")
                    : (editId ? "{{ route('project.child.update') }}" : "{{ route('project.child.store') }}");

                $.post(url, form.serialize(), function (res) {
                    if (res.status === 'success') {
                        Swal.fire('Success', 'Changes saved!', 'success').then(() => location.reload());
                    }
                });
            });

            // ১১. User tooltip on c-users column hover
            let $tip = $('#user-float-tip');

            $(document).on('mouseenter', '.dd-handle .c-users.has-tip', function () {
                let names = $(this).data('names').toString().split('|').map(n => '● ' + n).join('\n');
                $tip.css('white-space', 'pre').text(names).show();
                let r = this.getBoundingClientRect();
                $tip.css({
                    top:  r.top - $tip.outerHeight() - 8,
                    left: r.left + r.width / 2 - $tip.outerWidth() / 2
                });
            }).on('mouseleave', '.dd-handle .c-users.has-tip', function () {
                $tip.hide();
            });

        });
    </script>
@endpush