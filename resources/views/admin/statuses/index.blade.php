@extends('layouts.backend.app')

@section('admin_content')
    <div class="page-wrapper">
        <div class="page-content">

            <h6 class="mb-0 text-uppercase">Statuses</h6>
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
                        <a class="btn btn-success" href="{{ route('status.create') }}">Add Status</a>
                        <br><br>
                    </div>

                    <div class="table-responsive">
                        <table id="statusTable" class="table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>label</th>
                                <th>Project</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($statuses as $status)
                                <tr>
                                    <td>{{ $status->id }}</td>
                                    <td>{{ $status->label }}</td>
                                    <td>{{ $status->project?->title }}</td>
                                    <td>{{ $status->category }}</td>
                                    <td>
                                        <div>
                                            <a href="{{ route('status.edit',$status->id) }}" class="btn btn-warning">Edit</a>
                                            <a href="#"
                                               class="btn btn-danger"
                                               onclick="event.preventDefault(); document.getElementById('delete-form-{{ $status->id }}').submit();">
                                                Delete
                                            </a>

                                            <form id="delete-form-{{ $status->id }}"
                                                  action="{{ route('status.destroy', $status->id) }}"
                                                  method="POST" style="display:none;">
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
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            var table = $('#statusTable').DataTable({
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
@endpush