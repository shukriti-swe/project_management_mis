@extends('layouts.backend.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

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
@endpush
@section('admin_content')

    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <h6 class="mb-0 text-uppercase">Layer Types</h6>
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
                        <a class="btn btn-success" href="{{ route('layerType.create') }}">Add Layer Type</a>
                        <br><br>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($layerTypes as $layerType)
                                <tr>
                                    <td>{{ $layerType->id }}</td>
                                    <td>{{ $layerType->title }}</td>
                                    <td>
                                        @if($layerType->status==1)
                                            {{ 'Active' }}
                                        @else
                                            {{ 'Inactive' }}
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('layerType.edit',$layerType->id) }}" class="btn btn-warning">Edit</a>
                                            <a href="{{ route('layerType.destroy', $layerType->id) }}"
                                               class="btn btn-danger"
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $layerType->id }}').submit();">
                                                Delete
                                            </a>
                                            <form id="delete-form-{{ $layerType->id }}"
                                                  action="{{ route('layerType.destroy', $layerType->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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

@endsection

