@extends('layouts.backend.app')

@section('admin_content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.fancytree/dist/skin-lion/ui.fancytree.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <style>
        table {
            width: 100%;
        }

        td, th {
            padding: 8px;
            border-bottom: 1px solid #eee;
            /*border-bottom: none!important;*/
        }

        /* Prevent row breaking */
        #treeTable {
            table-layout: fixed;
            width: 100%;
        }

        /* All cells single-line */
        #treeTable th,
        #treeTable td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
        }

        #treeTable th:first-child,
        #treeTable td:first-child {
            width: 35%;
        }

        /* Row height consistency */
        #treeTable td {
            height: 40px;
            padding: 0;
        }

        /* First column (tree) */
        #treeTable td:first-child {
            overflow: visible; /* allow indentation + expand icon */
        }

        #treeTable tr.fancytree-active,
        #treeTable tr.fancytree-focused,
        #treeTable tr.fancytree-selected,
        #treeTable tr.fancytree-selected.fancytree-active {
            background-color: transparent !important;
            color: inherit !important;
        }

        #treeTable tr:hover {
            background-color: #f9f9f9 !important;
        }

        #treeTable tr.fancytree-active,
        #treeTable tr.fancytree-focused {
            background-color: #f3f4f6 !important;
        }

        /* Fix fancytree node layout */
        .fancytree-node {
            display: flex !important;
            width: 100% !important;
            min-width: 0 !important;
            align-items: center;
            gap: 6px;
        }

        .fancytree-node .fancytree-icon {
            display: none;
        }

        .fancytree-expanded > .fancytree-node .fancytree-expander::before {
            transform: rotate(90deg);
        }

        /* Prevent icon/title wrapping */
        .fancytree-title {
            display: inline-block;
            height: 40px;          /* match row */
            line-height: 40px;     /* vertical centering */

            flex: 1;
            min-width: 0;

            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        /* Optional: make columns visually cleaner */
        #treeTable th {
            font-weight: 600;
            background: #f8fafc;
        }

        /* Action buttons inline */
        #treeTable td:last-child button {
            margin-right: 5px;
        }

        .action-wrap a {
            font-size: 15px;
            color: #94a3b8;
            transition: 0.15s;
            display: inline-block;
        }

        .action-wrap a:hover {
            transform: scale(1.2);
        }

        .action-wrap .text-success:hover {
            color: #16a34a !important;
        }

        .action-wrap .text-primary:hover {
            color: #2563eb !important;
        }

        .action-wrap .text-danger:hover {
            color: #dc2626 !important;
        }

        .action-wrap .text-info:hover {
            color: #0891b2 !important;
        }
    </style>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="card">
                <div class="card-body">

                    <table id="treeTable" class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th>Users</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.fancytree/dist/jquery.fancytree-all-deps.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        window.treeData = @json($tree);
    </script>

    <script>
        $(function () {

            $("#treeTable").fancytree({

                extensions: ["table", "dnd5"],

                source: window.treeData,

                table: {
                    indentation: 20,
                    nodeColumnIdx: 0
                },

                renderColumns: function (event, data) {
                    const node = data.node;
                    const d = node.data;
                    const cells = $(node.tr).find(">td");

                    // existing
                    cells.eq(1).text(
                        d.start_time
                            ? moment(d.start_time).format('DD MMM, YY, hh:mmA')
                            : ''
                    );

                    cells.eq(2).text(
                        d.end_time
                            ? moment(d.end_time).format('DD MMM, YY, hh:mmA')
                            : ''
                    );
                    cells.eq(3).text(d.status || '');
                    cells.eq(4).text((d.users || []).join(', '));

                    const isProject = d.type === 'project';

                    const id = node.key;

                    cells.eq(5).html(`
        <div class="action-wrap">
            <a href="javascript:;" class="date-picker-btn text-info" data-id="${id}" title="Set Date">
                <i class="bx bx-calendar"></i>
            </a>

            <a href="javascript:;" class="open-add-modal text-success ms-1"
               data-project="${isProject ? id.replace('p', '') : d.project_id || ''}"
               data-parent="${isProject ? '' : id}"
               title="Add ${isProject ? 'Layer' : 'Child Layer'}">
                <i class="bx bx-plus-circle"></i>
            </a>

            ${
                        isProject
                            ? `<a href="javascript:;" class="edit-project ms-1 text-primary"
                        data-id="${id.replace('p', '')}" title="Edit Project">
                        <i class="bx bx-edit-alt"></i>
                   </a>`
                            : `<a href="javascript:;" class="edit-layer ms-1 text-muted"
                        data-id="${id}" title="Edit Layer">
                        <i class="bx bx-edit-alt"></i>
                   </a>`
                    }

            ${
                        isProject
                            ? `<a href="javascript:;" class="delete-project ms-1 text-danger"
                        data-id="${id.replace('p', '')}" title="Delete Project">
                        <i class="bx bx-trash"></i>
                   </a>`
                            : `<a href="javascript:;" class="delete-layer ms-1 text-danger"
                        data-id="${id}" title="Delete Layer">
                        <i class="bx bx-trash"></i>
                   </a>`
                    }
        </div>
    `);
                },

                dnd5: {
                    dragStart: () => true,
                    dragEnter: () => true,

                    dragDrop: function (node, data) {

                        data.otherNode.moveTo(node, data.hitMode);

                        console.log({
                            moved: data.otherNode.key,
                            target: node.key,
                            mode: data.hitMode // before / after / over
                        });

                        // 👉 later: send to backend
                    }
                }

            });

            $(document).on('click', '.date-picker-btn', function (e) {
                e.stopPropagation();
                let el = $(this);
                let id = el.attr('data-id');
                if (!el.data('daterangepicker')) {
                    let node = $("#treeTable").fancytree("getTree").getNodeByKey(id);
                    let d = node.data;

                    let startVal = d.start_time
                        ? moment(d.start_time)
                        : moment();

                    let endVal = d.end_time
                        ? moment(d.end_time)
                        : moment();

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


            // Modal Handlers
            $(document).on('click', '.open-add-modal', function () {

                $('#layerForm')[0].reset();

                $('#modal_layer_id').val('');
                $('#modal_project_id').val($(this).data('project'));
                $('#modal_parent_id').val($(this).data('parent'));

                $('#modal_name').val('');
                $('#modal_start_time').val('');
                $('#modal_end_time').val('');

                $('#layer_users').val(null).trigger('change');

                new bootstrap.Modal(document.getElementById('layerModal')).show();
            });

            $(document).on('click', '.edit-layer', function () {

                let id = $(this).data('id');

                let url = "{{ route('project.child.edit', ':id') }}".replace(':id', id);

                $.get(url, function (data) {

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

                    new bootstrap.Modal(document.getElementById('layerModal')).show();
                });
            });

            $(document).on('click', '.edit-project', function () {

                let id = $(this).data('id');

                let url = "{{ route('project.edit', ':id') }}".replace(':id', id);

                $.get(url, function (data) {

                    $('#edit_p_id').val(data.id);
                    $('#p_title').val(data.title);
                    $('#p_user_id').val(data.user_id);
                    $('#p_status_id').val(data.status_id);

                    new bootstrap.Modal(document.getElementById('projectModal')).show();
                });
            });

        });
    </script>

@endpush