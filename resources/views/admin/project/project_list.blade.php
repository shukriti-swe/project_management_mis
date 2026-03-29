@extends('layouts.backend.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <style>
        .table-responsive{
            overflow: visible!important;
        }
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
@endpush
@section('admin_content')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">

            <h6 class="mb-0 text-uppercase">Project</h6>
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

                    <div style="text-align:right;">
                        <a class="btn btn-success" href="{{ route('addProject') }}">Add Project</a>
                        <br><br>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover project-table" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $project)
                                <tr data-href="{{ route('projectDetails',$project->id) }}"
                                    style="cursor:pointer;">
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->start_date }}</td>
                                    <td>{{ $project->end_date }}</td>
                                    <td>
                                        <button
                                                class="btn btn-info viewCalendar"
                                                data-start="{{ $project->start_date }}"
                                                data-end="{{ $project->end_date }}"
                                                data-title="{{ $project->title }}">
                                            Calendar
                                        </button>
                                    </td>
                                    <td>
                                        <div class="btn-group">

                                            <button type="button" class="btn btn-warning">
                                                {{ match($project->status) {
                                                    1 => 'Not Start',
                                                    2 => 'Running',
                                                    3 => 'Pause',
                                                    4 => 'End',
                                                    default => 'Unknown'
                                                } }}
                                            </button>

                                            <button type="button"
                                                    class="btn btn-warning dropdown-toggle dropdown-toggle-split"
                                                    data-bs-toggle="dropdown">
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item change-status" href="#" data-project="{{ $project->id }}" data-status="1">Not Start</a></li>
                                                <li><a class="dropdown-item change-status" href="#" data-project="{{ $project->id }}" data-status="2">Running</a></li>
                                                <li><a class="dropdown-item change-status" href="#" data-project="{{ $project->id }}" data-status="3">Pause</a></li>
                                                <li><a class="dropdown-item change-status" href="#" data-project="{{ $project->id }}" data-status="4">End</a></li>
                                            </ul>

                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('editProject',$project->id) }}" class="btn btn-warning">Edit</a>
                                            <a href="{{ route('deleteProject',$project->id) }}" class="btn btn-danger">Delete</a>
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
    <div class="modal fade" id="calendarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Project Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="calendar"></div>
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
    <!--end page wrapper -->

    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->
    <footer class="page-footer">
        <p class="mb-0">Copyright © 2021. All right reserved.</p>
    </footer>


    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable({
                lengthChange: true,
                ordering: true,
                info: true
            });

            $('#statusFilter').on('change', function () {
                var filterValue = $(this).val();
                table.column(5).search(filterValue ? '^' + filterValue + '$' : '', true, false).draw();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let calendar;

            $('.viewCalendar').click(function (e) {

                e.stopPropagation();

                let start = $(this).data('start');
                let end = $(this).data('end');
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

    <script>
        $(document).on('click', '#example tbody tr', function (e) {

            // if clicked inside button/dropdown → ignore row click
            if ($(e.target).closest('.btn-group').length) return;

            let url = $(this).data('href');
            if (url) window.location = url;
        });
        $(document).on('click', '.change-status', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let projectId = $(this).data('project');
            let status = $(this).data('status');

            fetch(`/projects/${projectId}/status`, {
                method: 'POST', // safer for Apache
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    _method: 'PATCH',
                    status: status
                })
            })
                .then(res => res.json())
                .then(() => location.reload())
                .catch(err => console.error(err));
        });
    </script>

@endsection

