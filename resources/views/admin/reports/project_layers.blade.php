@extends('layouts.backend.app')

@section('admin_content')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .dd { max-width: 100% !important; width: 100% !important; }
    .dd-list { width: 100% !important; margin: 0; padding: 0; }
    .table-header, .dd-handle { 
        display: table !important; 
        table-layout: fixed !important; 
        width: 100% !important; 
        border-collapse: collapse;
        margin: 0 !important;
        padding: 0 !important;
        height: 52px !important;
    }

    .table-header { background: #2c3e50; color: white; font-weight: bold; border-radius: 4px 4px 0 0; }
    .dd-handle { background: #fff !important; border: 1px solid #dee2e6 !important; border-top: none !important; cursor: move; }

    .t-col { display: table-cell !important; vertical-align: middle !important; padding: 0 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    
    .c-name { width: 35%; padding-left: 45px !important; } 
    .c-cal { width: 5%; text-align: center; }
    .c-start { width: 12%; text-align: center; font-size: 11px; }
    .c-end { width: 12%; text-align: center; font-size: 11px; }
    .c-status { width: 10%; text-align: center; }
    .c-users { width: 15%; text-align: left; }
    .c-action { width: 11%; text-align: right; padding-right: 15px !important; position: relative; z-index: 10; }

    .dd-item > button { position: absolute !important; left: 8px !important; top: 15px !important; z-index: 11; }
    .user-tag { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 1px 4px; border-radius: 3px; font-size: 10px; margin: 1px; display: inline-block; }
    .date-picker-btn { cursor: pointer; color: #007bff; font-size: 18px; transition: 0.2s; }
    .date-picker-btn:hover { color: #0056b3; transform: scale(1.1); }

    .daterangepicker td.active, 
    .daterangepicker td.active:hover,
    .daterangepicker td.in-range {
        background-color: #007bff !important;
        border-color: transparent !important;
        color: #fff !important;
        border-radius: 0 !important;
    }

    .daterangepicker td.start-date { border-radius: 4px 0 0 4px !important; }
    .daterangepicker td.end-date { border-radius: 0 4px 4px 0 !important; }

    .daterangepicker td.available:hover, 
    .daterangepicker th.available:hover { background-color: #0056b3 !important; color: #fff !important; }
    .daterangepicker { z-index: 9999 !important; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .daterangepicker .btn-primary { background-color: #007bff !important; border-color: #007bff !important; }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <div class="card border shadow-none">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <input type="text" id="treeSearch" class="form-control form-control-sm w-25" placeholder="Search project or layer...">
                    <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="$('#projectForm')[0].reset(); $('#edit_p_id').val('');">+ New Project</button>
                </div>

                <div class="table-header d-none d-lg-table">
                    <div class="t-col c-name" style="padding-left: 15px !important;">Project / Layer Name</div>
                    <div class="t-col c-cal">Cal</div>
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
                                <div class="dd-handle" style="background: #f4f6f9 !important; border-left: 4px solid #007bff !important;">
                                    <div class="t-col c-name text-primary fw-bold" style="padding-left: 15px !important;">
                                        <i class='bx bxs-folder-open me-1'></i> {{ $project->title }}
                                    </div>
                                    <div class="t-col c-cal"></div>
                                    <div class="t-col c-start"></div>
                                    <div class="t-col c-end"></div>
                                    <div class="t-col c-status"></div>
                                    <div class="t-col c-users">
                                        @if($project->user) <span class="user-tag" style="background:#e0f2fe">{{ $project->user->name }}</span> @endif
                                    </div>
                                    <div class="t-col c-action">
                                        <button type="button" class="btn btn-success btn-xs open-add-modal py-0 px-2" data-project="{{ $project->id }}" data-parent="">+ Layer</button>
                                        <a href="javascript:;" class="edit-project ms-2 text-primary" data-id="{{ $project->id }}"><i class="bx bx-edit-alt"></i></a>
                                        <a href="javascript:;" class="delete-project ms-2 text-danger" data-id="{{ $project->id }}" data-has-child="{{ $project->layers->count() > 0 ? 'yes' : 'no' }}"><i class="bx bx-trash"></i></a>
                                    </div>
                                </div>

                                @php
                                    $renderLayers = function($layers, $projectId) use (&$renderLayers) {
                                        echo '<ol class="dd-list">';
                                        foreach ($layers->sortBy('position') as $layer) {
                                            $s_color = $layer->status->color ?? '#6c757d';
                                            $u_tags = $layer->users->map(fn($u) => "<span class='user-tag'>$u->name</span>")->implode('');
                                            $hasChildAttr = ($layer->children->count() > 0) ? 'yes' : 'no';

                                            echo '<li class="dd-item" data-id="'.$layer->id.'">
                                                <div class="dd-handle">
                                                    <div class="t-col c-name"><i class="bx bx-hash text-muted"></i> '.$layer->name.'</div>
                                                    <div class="t-col c-cal">
                                                        <i class="bx bx-calendar date-picker-btn" data-id="'.$layer->id.'"></i>
                                                    </div>
                                                    <div class="t-col c-start"><span class="start-txt-'.$layer->id.'">'.($layer->start_time ? date('d M, Y', strtotime($layer->start_time)) : '---').'</span></div>
                                                    <div class="t-col c-end"><span class="end-txt-'.$layer->id.'">'.($layer->end_time ? date('d M, Y', strtotime($layer->end_time)) : '---').'</span></div>
                                                    <div class="t-col c-status"><span class="badge rounded-pill" style="background:'.$s_color.'; font-size:9px;">'.($layer->status->label ?? 'N/A').'</span></div>
                                                    <div class="t-col c-users">'.$u_tags.'</div>
                                                    <div class="t-col c-action">
                                                        <a href="javascript:;" class="open-add-modal text-success" data-project="'.$projectId.'" data-parent="'.$layer->id.'"><i class="bx bx-plus-circle"></i></a>
                                                        <a href="javascript:;" class="edit-layer ms-2 text-muted" data-id="'.$layer->id.'"><i class="bx bx-edit-alt"></i></a>
                                                        <a href="javascript:;" class="delete-layer ms-2 text-danger" data-id="'.$layer->id.'" data-has-child="'.$hasChildAttr.'"><i class="bx bx-trash"></i></a>
                                                    </div>
                                                </div>';
                                            if ($layer->children->count() > 0) { $renderLayers($layer->children, $projectId); }
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="layerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="layerForm">@csrf
                <input type="hidden" name="project_id" id="modal_project_id">
                <input type="hidden" name="parent_id" id="modal_parent_id">
                <input type="hidden" name="layer_id" id="modal_layer_id">
                <div class="modal-header bg-dark text-white py-2"><h6 class="modal-title">Layer Setup</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="fw-bold small">Layer Name</label><input type="text" name="name" id="modal_name" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold small">Assign Users</label><select name="user_ids[]" id="layer_users" class="form-control select2-multiple" multiple="multiple">@foreach($users as $u)<option value="{{$u->id}}">{{$u->name}}</option>@endforeach</select></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="small fw-bold">Status</label><select name="status_id" id="modal_status_id" class="form-select">@foreach($statuses as $s)<option value="{{$s->id}}">{{$s->label}}</option>@endforeach</select></div>
                        <div class="col-6 mb-3"><label class="small fw-bold">Start Date</label><input type="date" name="start_time" id="modal_start_time" class="form-control"></div>
                        <div class="col-12 mb-3"><label class="small fw-bold">End Date</label><input type="date" name="end_time" id="modal_end_time" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary" id="saveBtn">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="projectForm">@csrf
                <input type="hidden" name="project_id" id="edit_p_id">
                <div class="modal-header bg-primary text-white py-2"><h6 class="modal-title">New Project</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="fw-bold small">Title</label><input type="text" name="title" id="p_title" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold small">Manager</label><select name="user_id" id="p_user_id" class="form-select" required><option value="">Select Manager</option>@foreach($users as $u)<option value="{{$u->id}}">{{$u->name}}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="fw-bold small">Status</label><select name="status_id" id="p_status_id" class="form-select">@foreach($statuses as $s)<option value="{{$s->id}}">{{$s->label}}</option>@endforeach</select></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary" id="p_save_btn">Create Project</button></div>
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
$(document).ready(function() {
    // ১. Nestable Init
    $('#nestable').nestable({ 
        maxDepth: 10,
        callback: function(l, e) {
            $.post("{{ route('layers.reorder') }}", { _token: "{{ csrf_token() }}", hierarchy: $('#nestable').nestable('serialize') });
        }
    });

    // ২. Select2 Init
    $('.select2-multiple').select2({ width: '100%', dropdownParent: $('#layerModal') });

    // ৩. Add Layer Modal Open
    $(document).on('click', '.open-add-modal', function() {
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

    // ৪. Edit Layer
    $(document).on('click', '.edit-layer', function() {
        let id = $(this).attr('data-id');
        let editUrl = "{{ route('project.child.edit', ':id') }}".replace(':id', id);
        $('#layerForm')[0].reset();
        $.get(editUrl, function(data) {
            $('#modal_layer_id').val(data.id);
            $('#modal_project_id').val(data.project_id);
            $('#modal_parent_id').val(data.parent_id);
            $('#modal_name').val(data.name);
            $('#modal_status_id').val(data.status_id);
            
            function formatDate(dateStr) {
                if (!dateStr) return '';
                let date = new Date(dateStr);
                return date.toISOString().split('T')[0]; 
            }
            $('#modal_start_time').val(formatDate(data.start_time));
            $('#modal_end_time').val(formatDate(data.end_time));

            if(data.users) {
                $('#layer_users').val(data.users.map(u => u.id)).trigger('change');
            }
            $('#layerModal .modal-title').text('Edit Layer Setup');
            $('#layerModal').modal('show');
        });
    });

    // ৪.১ Edit Project (ID গুলো ফিক্স করা হয়েছে)
    $(document).on('click', '.edit-project', function() {
        let id = $(this).attr('data-id');
        let url = "{{ route('project.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            $('#edit_p_id').val(data.id);
            $('#p_title').val(data.title); // এই ID টি ফর্মে যোগ করেছি
            $('#p_user_id').val(data.user_id); // এই ID টি ফর্মে যোগ করেছি
            $('#p_status_id').val(data.status_id); // এই ID টি ফর্মে যোগ করেছি
            $('#projectModal .modal-title').text('Edit Project');
            $('#p_save_btn').text('Update Project');
            $('#projectModal').modal('show');
        });
    });

    // ৫. Delete Layer
    $(document).on('click', '.delete-layer', function() {
        let id = $(this).attr('data-id');
        let hasChild = $(this).attr('data-has-child');
        if(hasChild === 'yes') {
            Swal.fire('Access Denied!', 'Please delete child layers first.', 'error');
            return;
        }
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('project.child.delete', ':id') }}".replace(':id', id);
                $.post(url, { _token: "{{ csrf_token() }}", _method: 'DELETE' }, function(res) {
                    if(res.status === 'success') location.reload();
                });
            }
        });
    });

    // ৫.১ Delete Project
    $(document).on('click', '.delete-project', function() {
        let id = $(this).attr('data-id');
        if($(this).attr('data-has-child') === 'yes') {
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
                $.post(url, { _token: "{{ csrf_token() }}", _method: 'DELETE' }, function(res) {
                    if(res.status === 'success') location.reload();
                });
            }
        });
    });

    // ৬. Calendar Update
    $(document).on('click', '.date-picker-btn', function(e) {
        e.stopPropagation();
        let el = $(this);
        let id = el.attr('data-id');
        if (!el.data('daterangepicker')) {
            let currentStart = $('.start-txt-' + id).text().trim();
            let currentEnd = $('.end-txt-' + id).text().trim();
            let startVal = (currentStart !== '---') ? moment(currentStart, 'DD MMM, YYYY') : moment();
            let endVal = (currentEnd !== '---') ? moment(currentEnd, 'DD MMM, YYYY') : moment();

            el.daterangepicker({
                startDate: startVal, endDate: endVal, opens: 'left',
                locale: { format: 'YYYY-MM-DD' }
            });
            el.data('daterangepicker').show();
            el.on('apply.daterangepicker', function(ev, picker) {
                $.post("{{ route('project.child.updateDates') }}", {
                    _token: "{{ csrf_token() }}",
                    layer_id: id,
                    start_time: picker.startDate.format('YYYY-MM-DD'),
                    end_time: picker.endDate.format('YYYY-MM-DD')
                }, function(res) { location.reload(); });
            });
        } else { el.data('daterangepicker').show(); }
    });

    // ৭. Form Submission
    $('#layerForm, #projectForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let isProject = (form.attr('id') === 'projectForm');
        let editId = isProject ? $('#edit_p_id').val() : $('#modal_layer_id').val();
        
        let url = isProject ? (editId ? "{{ route('project.update') }}" : "{{ route('project.store') }}") : 
                             (editId ? "{{ route('project.child.update') }}" : "{{ route('project.child.store') }}");
        
        $.post(url, form.serialize(), function(res) {
            if(res.status === 'success') {
                Swal.fire('Success', 'Changes saved!', 'success').then(() => location.reload());
            }
        });
    });
});
</script>
@endpush