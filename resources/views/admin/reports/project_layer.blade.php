@extends('layouts.backend.app')

@section('admin_content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css" rel="stylesheet">

    <style>

    </style>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <div id="myGrid" class="ag-theme-alpine" style="height: 600px;"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>

    <script>
        window.rows = @json($rows);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const gridOptions = {

                columnDefs: [
                    {
                        field: "title",
                        headerName: "Project / Layer",
                        flex: 2,
                        rowDrag: true
                    },
                    {
                        field: "start_time",
                        headerName: "Start",
                        width: 150
                    },
                    {
                        field: "end_time",
                        headerName: "End",
                        width: 150
                    },
                    {
                        field: "status",
                        headerName: "Status",
                        width: 120
                    },
                    {
                        field: "users",
                        headerName: "Users",
                        width: 200,
                        valueFormatter: p => (p.value || []).join(', ')
                    }
                ],

                rowData: window.rows,

                treeData: true,
                treeDataParentIdField: "parentId",

                getRowId: params => params.data.id,

                groupDefaultExpanded: -1
            };

            agGrid.createGrid(document.getElementById('myGrid'), gridOptions);
        });
    </script>
@endpush