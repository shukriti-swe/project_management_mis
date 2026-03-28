@extends('layouts.backend.app')

@section('admin_content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.css"/>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

    <style>
        /* Select2 পজিশন এবং Z-index ফিক্স */
        .select2-container {
            display: block !important;
            width: 100% !important;
        }

        .select2-container--open {
            z-index: 10000 !important; /* মোডালের z-index সাধারণত 1055 হয়, তাই এটি আরও বেশি হওয়া চাই */
        }

        /* Multiple Select এর ডিজাইন ফিক্স */
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 40px !important;
            padding: 2px 8px !important;
            border: 1px solid #ced4da !important;
            display: flex !important;
            align-items: center !important;
        }

        /* ইনপুট ফিল্ড পজিশন ফিক্স */
        .select2-container--bootstrap4 .select2-search--inline .select2-search__field {
            margin-top: 0 !important;
            height: 32px !important;
        }

        /* Single Select এর হাইট ফিক্স */
        .select2-container--bootstrap4 .select2-selection--single {
            height: 38px !important;
            line-height: 38px !important;
        }

        /* ড্রপডাউন লিস্টের পজিশন যাতে উল্টাপাল্টা না হয় */
        .select2-dropdown {
            border: 1px solid #ced4da !important;
            z-index: 10001 !important;
        }
    </style>

    <style>
        /* CHILD CARD GRID */

        .child-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: .18s;
        }

        .child-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
            border-color: #dee2e6;
        }

        .child-card-body {
            padding: 18px;
            flex-grow: 1;
        }

        .child-card-footer {
            border-top: 1px solid #f1f3f5;
            padding: 12px 18px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .child-title {
            font-size: 15px;
            font-weight: 600;
        }

        .child-description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.5;
            margin-top: 6px;

            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;

            overflow: hidden;
            text-overflow: ellipsis;

            height: calc(1.5em * 3); /* reserve space for 3 lines */
        }

        .child-dates {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-top: 12px;
        }

        .child-actions .btn {
            font-size: 12px;
            padding: 4px 10px;
        }
    </style>

    <style>
        /* overall font */
        .fc {
            font-family: system-ui, -apple-system, sans-serif;
            color: #444;
        }

        /* header title */
        .fc-toolbar-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        /* day numbers */
        .fc-daygrid-day-number {
            color: #555;
            font-weight: 500;
        }

        /* calendar cell hover */
        .fc-daygrid-day:hover {
            background: #f8f9fa;
        }

        /* event highlight */
        .fc-bg-event {
            background: #0d6efd20 !important;
        }

        /* toolbar buttons */
        .fc .fc-button {
            background: #fff;
            border: 1px solid #dee2e6;
            color: #444;
        }

        .fc .fc-button:hover {
            background: #f1f3f5;
            color: #444;
        }

        /* remove heavy borders */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #f1f3f5;
        }

        .fc-event-title {
            text-align: center;
            width: 100%;
        }

        .fc-daygrid-event {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-body {
            padding: 25px;
        }
    </style>

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <h6 class="mb-0 text-uppercase">Layers</h6>
            <hr/>
            <div class="card">
                <div class="card-body">

                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}" role="alert">
                                    @if($msg == 'success')
                                        <strong><i class="icon fa fa-check"></i></strong>
                                    @elseif($msg == 'warning')
                                        <strong><i class="icon fa fa-warning"></i></strong>
                                    @elseif($msg == 'info')
                                        <strong><i class="icon fa fa-info"></i></strong>
                                    @elseif($msg == 'danger')
                                        <strong><i class="icon fa fa-ban"></i></strong>
                                    @endif
                                    {{ Session::get('alert-' . $msg) }}
                                </p>
                            @endif
                        @endforeach
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Filter:</label>
                            <select id="statusFilter" class="form-select" style="width: 200px; display: inline-block;">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>

                        <div class="col-md-6" style="text-align:right;">
                            <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLayerModal">Add
                                Layer</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Project</th>
                                <th>user</th>
                                <th>Layer</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($layers as $layer)
                                <tr>
                                    <td>{{ $layer->id }}</td>
                                    <td>{{ $layer->name }}</td>
                                    <td style="width: 200px;">
                                        <select class="form-select inline-select" data-id="{{ $layer->id }}" data-column="project_id">
                                            <option value="">No Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ $layer->project_id == $project->id ? 'selected' : '' }}>
                                                    {{ $project->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td style="width: 250px;">
                                        <select class="form-select inline-select-multiple" data-id="{{ $layer->id }}" data-column="assigned_user_ids" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ in_array($user->id, $layer->users->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td style="width: 200px;">
                                        <select class="form-select inline-select" data-id="{{ $layer->id }}" data-column="parent_id">
                                            <option value="">No Parent</option>
                                            @foreach($layers as $l)
                                                @if($l->id != $layer->id) <option value="{{ $l->id }}" {{ $layer->parent_id == $l->id ? 'selected' : '' }}>
                                                    {{ $l->name }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $layer->start_time }}</td>
                                    <td>{{ $layer->end_time }}</td>
                                    <td>
                                        <button
                                                class="btn btn-info viewCalendar"
                                                data-start="{{ $layer->start_time }}"
                                                data-end="{{ $layer->end_time }}"
                                                data-title="{{ $layer->title }}">
                                            Calendar
                                        </button>
                                    </td>
                                    <td data-filter="{{ $layer->status_id == 1 ? 'Active' : 'In-Active' }}">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary update-status-btn"
                                                data-id="{{ $layer->id }}" 
                                                data-index="{{ $layer->project_id }}"
                                                data-current-status="{{ $layer->status_id == 1 ? 'Active' : 'In-Active' }}">
                                            {{ $layer->status_id == 1 ? 'Active' : 'In-Active' }} <i class="bx bx-edit-alt"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('layer.edit',$layer->id) }}" class="btn btn-warning">Edit</a>
                                            <a href="{{ route('layer.destroy',$layer->id) }}" class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            <h6 class="mb-0 text-uppercase">Card View</h6>
            <hr/>

        </div>
    </div>


    <footer class="page-footer">
        <p class="mb-0">Copyright © 2021. All right reserved.</p>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>

        $(document).ready(function () {
            var table = $('#example').DataTable({
        lengthChange: true,
        ordering: true,
        info: true
    });

    $('#statusFilter').on('change', function() {
        var val = $(this).val(); // ১ অথবা ০
        var searchText = '';

        if (val === "1") {
            searchText = 'Active';
        } else if (val === "0") {
            searchText = 'In-Active';
        }

        // কলাম ৮ (Status) ফিল্টার করবে। 
        // true, false এর মানে হলো smart search বন্ধ করে exact match করা।
        table.column(8).search(searchText ? '^' + searchText + '$' : '', true, false).draw();
    });

            function initSelect2() {
                $('.single-select, .single-select-no-parent').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#addLayerModal')
                });

                $('.multiple-select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Select users...",
                    allowClear: true,
                    dropdownParent: $('#addLayerModal')
                });

                $('#layerTypeSelect').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    tags: true,
                    dropdownParent: $('#addLayerModal'),
                    createTag: function (params) {
                        var term = $.trim(params.term);
                        if (term === '') return null;
                        return {id: term, text: term, newTag: true};
                    }
                });
            }

            $('#addLayerModal').on('shown.bs.modal', function () {
                $('.select2-init').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#addLayerModal'), 
                    placeholder: "Select an option"
                });
            });

            $('#layerTypeSelect').on('select2:select', function (e) {
                var data = e.params.data;
                var $select = $(this);

                if (data.newTag) {
                    $.ajax({
                        url: "{{ route('layer-types.store') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: data.text
                        },
                        success: function (response) {
                            if (response.success) {
                                var newOption = new Option(data.text, response.id, false, true);
                                $select.find('option[value="' + data.id + '"]').remove();
                                $select.append(newOption).trigger('change');
                                alertify.success("New type added!");
                            }
                        }
                    });
                }
            });


            $('#addLayerForm').on('submit', function (e) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $('#saveLayerBtn');

                $btn.prop('disabled', true); 

                $.ajax({
                    url: $form.attr('action'),
                    method: "POST",
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            alertify.success('Layer saved successfully!');
                            $('#addLayerModal').modal('hide');
                            $form[0].reset();

     
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false); 
                        let msg = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        alertify.error(msg);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.inline-select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            $('.inline-select-multiple').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Select Users"
            });

            $(document).on('change', '.inline-select, .inline-select-multiple', function() {
                let $this = $(this);
                let id = $this.data('id');
                let column = $this.data('column');
                let value = $this.val();

                $.ajax({
                    url: "{{ route('layers.inlineUpdate') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        column: column,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            alertify.success('Updated successfully!');
                        }
                    },
                    error: function() {
                        alertify.error('Update failed!');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.inline-select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            $('.inline-select-multiple').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Select Users"
            });

            $(document).on('change', '.inline-select, .inline-select-multiple', function() {
                let $this = $(this);
                let id = $this.data('id');
                let column = $this.data('column');
                let value = $this.val();

                $.ajax({
                    url: "{{ route('layers.inlineUpdate') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        column: column,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            alertify.success('Updated successfully!');
                        }
                    },
                    error: function() {
                        alertify.error('Update failed!');
                    }
                });
            });
        });
    </script>

@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.6.0/pagination.min.js"></script>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const items = Array.from(document.querySelectorAll(".layer-item"));
            const container = document.getElementById("layerContainer");

            $('#layerPagination').pagination({

                dataSource: items,
                pageSize: 8,
                pageRange: 2,
                className: "paginationjs-theme-blue paginationjs-big",

                callback: function (data, pagination) {

                    container.innerHTML = "";

                    data.forEach(function (item) {
                        container.appendChild(item);
                    });

                    const start = (pagination.pageNumber - 1) * pagination.pageSize + 1;
                    const end = Math.min(
                        pagination.pageNumber * pagination.pageSize,
                        pagination.totalNumber
                    );

                    const total = pagination.totalNumber;

                    document.getElementById('layerStats').innerHTML =
                        `Showing ${start}-${end} of ${total} records`;
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let calendar;

            $('.viewCalendar').click(function (e) {

                e.stopPropagation();

                let start = $(this).data('start');
                let end = $(this).data('end');

                let endDate = new Date(end);
                endDate.setDate(endDate.getDate() + 1);

                // convert back to YYYY-MM-DD
                end = endDate.toISOString().split('T')[0];
                let title = $(this).data('title');

                $('#calendarModal').modal('show');

                setTimeout(function () {

                    let calendarEl = document.getElementById('calendar');

                    if (calendar) {
                        calendar.destroy();
                    }

                    calendar = new FullCalendar.Calendar(calendarEl, {

                        initialView: 'dayGridMonth',
                        height: 480,

                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek'
                        },

                        buttonText: {
                            today: 'Today',
                            month: 'Month',
                            week: 'Week'
                        },

                        events: [
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: true,
                                backgroundColor: '#e7f1ff',
                                borderColor: '#cfe2ff',
                                textColor: '#2c3e50'
                            }
                        ]

                    });

                    calendar.render();

                }, 200);

            });

        });
    </script>
@endpush

