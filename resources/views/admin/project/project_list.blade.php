@extends('layouts.backend.app')

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
                        <table id="example" class="table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Start date</th>
                                <th>End date</th>
                                {{--                                <th>FIle/image</th>--}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $project)
                                <tr onclick="window.location='{{ route('projectDetails',$project->id) }}'"
                                    style="cursor:pointer;">
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->start_date }}</td>
                                    <td>{{ $project->end_date }}</td>
                                    {{--                                <td style="text-align: center;">--}}
                                    {{--                                    <img style="height:70px;width:120px;" src="{{ asset('project/'.$project->image) }}">--}}
                                    {{--                                </td>--}}
                                    <td>
                                        @if($project->status==1)
                                            {{ 'Active' }}
                                        @else
                                            {{ 'In-Active' }}
                                        @endif
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
    $(document).ready(function() {
        var table = $('#example').DataTable({
            lengthChange: true,
            ordering: true,
            info: true
        });

        $('#statusFilter').on('change', function() {
            var filterValue = $(this).val();
            table.column(5).search(filterValue ? '^' + filterValue + '$' : '', true, false).draw();
        });
    });
</script>

@endsection

