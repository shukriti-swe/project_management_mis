@extends('layouts.backend.app')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.fancytree/dist/skin-lion/ui.fancytree.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        table {
            width: 100%;
        }

        td, th {
            padding: 8px;
            border-bottom: 1px solid #eee;
            /*border-bottom: none!important;*/
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .table-header h1 {
            font-size: 18px;
            font-weight: 600;
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
            height: 40px; /* match row */
            line-height: 40px; /* vertical centering */

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
    <style>
        :root {
            --bg: #f8fafc;
            --column-bg: #f1f5f9;
            --card-bg: #ffffff;

            --text: #111827;
            --muted: #6b7280;

            --border: #e5e7eb;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);

            --primary: #3b82f6;
            --success: #10b981;

            --radius: 6px;
        }

        /* =========================
           LARGE MODAL
        ========================= */
        .modal-xl {
            width: 70vw;
            height: 70vh;

            max-width: none;
            margin: 20px auto;

            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
            margin-bottom: 10px;
            padding-top: 0;
            gap: 20px;
        }

        .close {
            cursor: pointer;
            font-size: 24px;
            color: var(--muted);
        }

        .title-input-group {
            display: flex;
            flex-grow: 1;
            align-items: center;
            justify-content: start;
            gap: 8px;
        }

        /* Title input */
        .title-input {
            font-size: 16px;
            font-weight: 600;
            border: none;
            outline: none;
            background: transparent;
        }

        .inline-update-btn {
            padding: 12px 10px;
            border-radius: var(--radius);
            font-size: 14px;
            color: #FFFFFF;
            background-color: var(--primary);
        }

        .inline-update-btn:focus {
            outline: none;
        }

        /* =========================
           LAYOUT
        ========================= */
        .task-details-layout {
            display: flex;
            height: 100%;
            gap: 16px;
            overflow: hidden;
        }

        /* LEFT PANEL (3 parts) */
        .details-left {
            flex: 3;
            overflow-y: auto;
            padding: 40px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        /* RIGHT PANEL (2 parts) */
        .details-right {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* =========================
           SECTIONS
        ========================= */
        .tree-section,
        .log-section {
            flex: 1;
            overflow-y: auto;

            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 10px;
        }

        /* Section title */
        .section-title {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--muted);
        }

        /* =========================
           DETAIL FIELDS
        ========================= */
        .detail-group {
            margin-bottom: 20px;
        }

        .date-range-group {
            max-width: 50%;
        }

        .assigned-users-group label {
            margin-bottom: 20px !important;
        }

        .detail-group label {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 4px;
            display: block;
        }

        /* Inputs */
        .editable-input,
        textarea,
        select {
            width: 100%;
            padding: 6px 8px;
            font-size: 13px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        /* =========================
           USERS
        ========================= */
        .users {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* =========================
           TREE
        ========================= */
        .tree-node {
            padding-left: 10px;
            margin: 4px 0;
            font-size: 13px;
        }

        .tree-node-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
            text-overflow: ellipsis;

            line-height: 1.4;
            max-height: calc(1.4em * 2);
            cursor: pointer;
        }

        .tree-node-title-root {
            color: #666666;
            cursor: text;
        }

        .tree-node-title-root:hover {
            text-decoration: none !important;
        }

        .tree-node-title:hover {
            text-decoration: underline;
        }

        .tree-node.active {
            font-weight: 600;
            color: var(--primary);
        }

        .tree-children {
            margin-left: 12px;
            border-left: 1px dashed #ddd;
            padding-left: 8px;
        }

        /* =========================
           LOG
        ========================= */
        #detailsLog div {
            font-size: 12px;
            padding: 4px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .progress-inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .progress-inline .inline-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .subtasks-inline {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 4px;
            min-width: 60px;
        }

        .user-row {
            border: 1px solid var(--border);
            margin-bottom: 10px;
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 6px;
            border-radius: 6px;

            background: #f8fafc;
            position: relative;
        }

        #detailsUsers {
            margin-top: 20px;
        }

        .user-remove {
            position: absolute;
            top: -6px;
            right: -6px;

            width: 20px;
            height: 20px;

            border: none;
            border-radius: 50%;

            background: #535353;
            color: #fff;

            font-size: 14px; /* 🔥 slightly smaller */
            cursor: pointer;

            display: none;
            align-items: center;
            justify-content: center;

            line-height: 1; /* remove extra vertical spacing */
            padding: 0; /* remove default button padding */
        }

        .user-row:hover .user-remove {
            display: flex;
        }

        .user-row img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }

        /* DESCRIPTION */
        .description-view {
            font-size: 13px;
            line-height: 1.5;
        }

        /* TREE */
        .tree-node.active {
            font-weight: 600;
            color: var(--primary);
        }

        .status-btn {
            cursor: default !important;
        }

        #currentStatusBtn:hover {
            color: #FFF;
        }
    </style>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            font-size: 14px;
            background: var(--bg);
            color: var(--text);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            font-size: 18px;
            font-weight: 600;
        }

        .select2-container--default .select2-selection--single {
            height: 30px;
            padding: 2px 6px;
            font-size: 13px;
        }

        /* =========================
           BUTTONS
        ========================= */
        .btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            padding: 6px 10px;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Progress row improved */
        .progress-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Progress bar */
        .progress {
            flex: 1;
            height: 4px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--success);
        }

        /* Percentage */
        .progress-text {
            font-size: 11px;
            color: var(--muted);
            min-width: 30px;
            text-align: right;
        }

        /* =========================
           MODAL
        ========================= */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        /* Bigger modal */
        .modal-lg {
            max-width: 520px;
        }

        /* Form layout */
        .form-row {
            display: flex;
            gap: 10px;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* Improve select */
        select {
            appearance: none;
            background: #fff;
            cursor: pointer;
        }

        /* Select2 override (important) */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 4px;
            font-size: 13px;
        }

        .select2-container--default .select2-selection--single {
            height: 32px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        /* Dropdown */
        .select2-dropdown {
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        /* Reset plugin layout */
        .daterangepicker {
            font-size: 13px;
        }

        /* Fix buttons */
        .daterangepicker .drp-buttons {
            display: flex !important;
            justify-content: end !important;
            gap: 8px !important;
            padding: 8px !important;
        }

        .daterangepicker .drp-selected {
            /*color: red!important;*/
            margin: auto 0;
            font-weight: bold;
        }

        .daterangepicker .drp-buttons .btn {
            all: unset; /* wipe your global .btn */

            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        /* Apply button */
        .daterangepicker .applyBtn {
            background: #3b82f6 !important;
            color: #fff !important;

        }

        /* Cancel button */
        .daterangepicker .cancelBtn {
            margin-left: auto !important;
            background: #e5e7eb !important;
            color: #374151 !important;
        }

        /* Fix calendar spacing */
        .daterangepicker .calendar-table {
            border: none;
        }

        /* Fix inputs */
        .daterangepicker input {
            border: 1px solid #e5e7eb;
            padding: 4px;
        }

        /* Selected tags */
        .select2-selection__choice {
            background: #e5e7eb !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 2px 6px !important;
            font-size: 12px;
        }

        .modal-content {
            background: #fff;
            margin: 80px auto;
            padding: 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        /* =========================
           FORM
        ========================= */
        .form-group {
            margin-bottom: 12px;
        }

        label {
            font-size: 12px;
            margin-bottom: 4px;
            display: block;
        }

        input, textarea, select {
            width: 100%;
            padding: 6px 8px;
            font-size: 13px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .board {
                flex-direction: column;
            }
        }
    </style>
@endpush
@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <div class="table-header">
                        <h1>Projects Table</h1>

                        <button id="openAddProjectModal" class="btn">
                            + Add Project
                        </button>
                    </div>

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

    <div class="modal" id="projectDetailsModal">
        <div class="modal-content modal-xl">

            <!-- HEADER -->
            <div class="modal-header">
                <div class="title-input-group">
                    <input id="projectDetailsName" class="title-input"/>
                    <button id="updateProjectNameBtn" class="inline-update-btn">
                        <i class="fa fa-check"></i>
                    </button>
                </div>
                <span class="close" id="closeProjectDetailsModal">&times;</span>
            </div>

            <!-- BODY -->
            <div class="task-details-layout">

                <!-- LEFT -->
                <div class="details-left">

                    <!-- STATUS -->
                    <div class="detail-group">
                        <label>Status</label>

                        <div class="btn-group status-dropdown">
                            <button id="projectCurrentStatusBtn" class="btn status-btn"></button>
                            <button id="projectDropdownToggle"
                                    class="btn dropdown-toggle dropdown-toggle-split"></button>
                            <ul id="projectStatusDropdownMenu" class="dropdown-menu"></ul>
                        </div>
                    </div>

                    <!-- DATE -->
                    <div class="detail-group date-range-group">
                        <label>Date Range</label>
                        <input id="projectDateRange">
                    </div>

                    <!-- MANAGER -->
                    <div class="detail-group">
                        <label>Manager</label>
                        <select id="projectManagerSelect"></select>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="detail-group">
                        <label>Description</label>

                        <div id="projectDescription" class="description-view"></div>

                        <textarea id="projectDescriptionEditor" style="display:none;"></textarea>

                        <button id="updateProjectDescriptionBtn"
                                class="inline-update-btn"
                                style="display:none; margin-top: 10px; padding: 4px 10px;">
                            Update
                        </button>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="details-right">

                    <div class="log-section">
                        <div class="section-title">Activity</div>
                        <div id="projectDetailsLog"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="taskDetailsModal">
        <div class="modal-content modal-xl">

            <!-- HEADER -->
            <div class="modal-header">
                <div class="title-input-group">
                    <input id="detailsName" class="title-input"/>
                    <button id="updateLayerNameBtn" class="inline-update-btn"><i class="fa fa-check"></i></button>
                </div>
                <span class="close" id="closeDetailsModal">&times;</span>
            </div>

            <!-- BODY -->
            <div class="task-details-layout">

                <!-- LEFT -->
                <div class="details-left">

                    <!-- STATUS -->
                    <div class="detail-group">
                        <label>Status</label>

                        <div class="btn-group status-dropdown">
                            <button id="currentStatusBtn" class="btn status-btn"></button>
                            <button id="dropdown-toggle-split" class="btn dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown"></button>
                            <ul id="statusDropdownMenu" class="dropdown-menu"></ul>
                        </div>
                    </div>

                    <!-- PROGRESS -->
                    <div class="detail-group">

                        <div class="progress-inline">

                            <div class="inline-group">
                                <i id="detailsSubtaskIcon"></i>
                                <label>Subtasks: </label>
                                <div class="subtasks-inline">
                                    <span id="detailsSubtasks"></span>
                                </div>
                            </div>

                            <div class="inline-group">
                                <label>Progress</label>
                                <div class="progress">
                                    <div id="detailsProgressBar" class="progress-bar"></div>
                                </div>
                            </div>

                            <span id="detailsProgressText"></span>

                        </div>
                    </div>

                    <!-- DATE -->
                    <div class="detail-group date-range-group">
                        <label>Date Range</label>
                        <input id="detailsDateRange">
                    </div>

                    <!-- USERS -->
                    <div class="detail-group assigned-users-group">
                        <label>Assigned Users</label>
                        <select id="detailsUsersSelect" class="user-select2" multiple style="width:100%;"></select>
                        <div id="detailsUsers"></div>
                    </div>

                    <!-- DESCRIPTION (FIXED) -->
                    <div class="detail-group">
                        <label>Description</label>

                        <!-- VIEW -->
                        <div id="detailsDescription" class="description-view"></div>

                        <!-- EDIT (hidden initially) -->
                        <textarea id="detailsDescriptionEditor" style="display:none;"></textarea>

                        <!-- UPDATE BTN -->
                        <button id="updateDescriptionBtn" class="inline-update-btn"
                                style="display:none; margin-top: 10px; padding: 4px 10px;">
                            Update
                        </button>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="details-right">

                    <div class="tree-section">
                        <div class="section-title">Structure</div>
                        <div id="detailsTree"></div>
                    </div>

                    <div class="log-section">
                        <div class="section-title">Activity</div>
                        <div id="detailsLog"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="addProjectModal">
        <div class="modal-content modal-lg">

            <div class="modal-header">
                <h2 class="modal-title">Create Project</h2>
                <span class="close" id="closeAddProjectModal">&times;</span>
            </div>

            <form id="addProjectForm">

                <!-- Title -->
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" id="addProjectTitle" required>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label>Status</label>
                    <select id="addProjectStatus">
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->label}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Manager -->
                <div class="form-group">
                    <label>Manager</label>
                    <select id="addProjectUser">
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div class="form-group">
                    <label>Date Range</label>
                    <input type="text" id="addProjectDateRange" readonly>
                </div>

                <button type="submit" class="btn">
                    Create Project
                </button>

            </form>
        </div>
    </div>

    <div class="modal" id="addLayerModal">
        <div class="modal-content modal-lg">

            <div class="modal-header">
                <h2 class="modal-title">Create Layer</h2>
                <span class="close" id="closeAddLayerModal">&times;</span>
            </div>

            <form id="addLayerForm">

                <!-- Name -->
                <div class="form-group">
                    <label>Layer Name</label>
                    <input type="text" id="addLayerName" required>
                </div>

                <input type="hidden" id="addLayerProjectId">
                <input type="hidden" id="addLayerParentId">

                <!-- Status -->
                <div class="form-group">
                    <label>Status</label>
                    <select id="addLayerStatus">
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->label}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Users -->
                <div class="form-group">
                    <label>Assign Users</label>
                    <select id="addLayerUsers" multiple>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div class="form-group">
                    <label>Date Range</label>
                    <input type="text" id="addLayerDateRange" readonly>
                </div>

                <button type="submit" class="btn">
                    Create Layer
                </button>

            </form>
        </div>
    </div>

@endsection

@push('js')
    {{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/jquery.fancytree/dist/jquery.fancytree-all-deps.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.treeData = @json($tree);
        window.allStatuses = @json($statuses);
        window.allUsers = @json($users);
    </script>

    <script>
        async function refreshTreeFunction() {
            try {
                const res = await fetch("{{ route('projectLayersTree') }}");
                const treeData = await res.json();

                const tree = $.ui.fancytree.getTree("#treeTable");

                const expandedKeys = [];

                tree.getRootNode().visit(function (node) {
                    if (node.isExpanded()) {
                        expandedKeys.push(node.key);
                    }
                });

                const expandedSet = new Set(expandedKeys);

                tree.reload(treeData);

                setTimeout(() => {
                    tree.getRootNode().visit(function (node) {
                        if (expandedSet.has(node.key)) {
                            node.setExpanded(true);
                        }
                    });
                }, 0);

            } catch (e) {
                console.error('Failed to refresh tree', e);
            }
        }

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

                    const row = $(node.tr);
                    row.removeAttr('style');

                    let isOverdue = false;

                    if (d.end_time) {
                        const end = moment.utc(d.end_time).local();

                        if (end.isValid()) {
                            isOverdue = end.isBefore(moment(), 'minute');
                        }
                    }

                    const statusCategory = (d.status_category || '').toLowerCase();

                    const excluded = ['backlog', 'done', 'canceled'];

                    if (isOverdue && !excluded.includes(statusCategory)) {
                        row.attr(
                            'style',
                            'background-color: rgba(239,68,68,0.08) !important;'
                        );
                    }

                    cells.eq(1).text(
                        d.start_time
                            ? (
                                d.type === 'project'
                                    ? moment.utc(d.start_time).local().format('DD MMM, YY')
                                    : moment.utc(d.start_time).local().format('DD MMM, YY, hh:mmA')
                            )
                            : ''
                    );

                    cells.eq(2).text(
                        d.end_time
                            ? (
                                d.type === 'project'
                                    ? moment.utc(d.end_time).local().format('DD MMM, YY')
                                    : moment.utc(d.end_time).local().format('DD MMM, YY, hh:mmA')
                            )
                            : ''
                    );

                    cells.eq(3).text(d.status || '');
                    cells.eq(4).text((d.users || []).join(', '));

                    const isProject = d.type === 'project';

                    const id = d.id;
                    const key = node.key;

                    cells.eq(5).html(`
        <div class="action-wrap">
            <a href="javascript:;" class="date-picker-btn text-info" data-key="${key}" title="Set Date">
                <i class="bx bx-calendar"></i>
            </a>

            <a href="javascript:;" class="open-add-modal text-success ms-1"
               data-project="${isProject ? d.id : d.project_id || ''}"
               data-parent="${isProject ? '' : node.key}"
               title="Add ${isProject ? 'Layer' : 'Child Layer'}">
                <i class="bx bx-plus-circle"></i>
            </a>
            ${
                        isProject
                            ? `<a href="javascript:;" class="delete-project ms-1 text-danger"
                        data-key="${key}" data-id="${id}" title="Delete Project">
                        <i class="bx bx-trash"></i>
                   </a>`
                            : `<a href="javascript:;" class="delete-layer ms-1 text-danger"
                        data-key="${key}" data-id="${id}" title="Delete Layer">
                        <i class="bx bx-trash"></i>
                   </a>`
                    }
        </div>
    `);
                },

                dnd5: {
                    dragStart: (node, data) => {
                        return node.data.type !== 'project';
                    },
                    dragEnter: function (node, data) {

                        const sourceNode = data.otherNode;

                        // prevent dropping into itself
                        if (node === sourceNode) return false;

                        // prevent dropping into own descendant
                        if (node.isDescendantOf(sourceNode)) return false;

                        const source = sourceNode.data;
                        const target = node.data;

                        // block project drag
                        if (source.type === 'project') return false;

                        // block drop ON project
                        if (target.type === 'project') return false;

                        // block cross-project move
                        if (source.project_id !== target.project_id) return false;

                        return true;
                    },

                    {{--dragDrop: async function (node, data) {--}}

                            {{--    data.otherNode.moveTo(node, data.hitMode);--}}

                            {{--    const tree = $.ui.fancytree.getTree("#treeTable");--}}

                            {{--    function buildHierarchy(node) {--}}
                            {{--        return {--}}
                            {{--            id: node.data.id,--}}
                            {{--            children: (node.children || []).map(child => buildHierarchy(child))--}}
                            {{--        };--}}
                            {{--    }--}}

                            {{--    const hierarchy = tree.getRootNode().children.map(n => buildHierarchy(n));--}}

                            {{--    try {--}}
                            {{--        await fetch("{{ route('layers.reorder') }}", {--}}
                            {{--            method: "POST",--}}
                            {{--            headers: {--}}
                            {{--                "Content-Type": "application/json",--}}
                            {{--                "X-CSRF-TOKEN": document--}}
                            {{--                    .querySelector('meta[name="csrf-token"]')--}}
                            {{--                    .getAttribute('content')--}}
                            {{--            },--}}
                            {{--            body: JSON.stringify({ hierarchy })--}}
                            {{--        });--}}

                            {{--        // ✅ refresh after save--}}
                            {{--        await refreshTreeFunction();--}}

                            {{--    } catch (e) {--}}
                            {{--        console.error('Reorder failed', e);--}}
                            {{--    }--}}
                            {{--}--}}
                    dragDrop: async function (node, data) {

                        const tree = $.ui.fancytree.getTree("#treeTable");

                        const tempMove = () => {
                            data.otherNode.moveTo(node, data.hitMode);
                        };

                        tempMove(); // UI preview

                        function buildHierarchy(node) {

                            // ❌ skip project nodes completely
                            if (node.data.type === 'project') {
                                return (node.children || []).map(buildHierarchy).flat();
                            }

                            return {
                                id: node.data.id,
                                children: (node.children || [])
                                    .map(child => buildHierarchy(child))
                                    .flat()
                            };
                        }

                        const hierarchy = tree.getRootNode().children
                            .map(projectNode => {
                                return (projectNode.children || []).map(buildHierarchy);
                            })
                            .flat();

                        try {
                            await fetch("{{ route('layers.reorder') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document
                                        .querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({hierarchy})
                            });

                            await refreshTreeFunction();

                        } catch (e) {
                            console.error('Reorder failed', e);

                            // 🔥 rollback UI
                            await refreshTreeFunction();
                        }
                    }
                }

            });

            $(document).on('click', '.date-picker-btn', function (e) {
                e.stopPropagation();
                let el = $(this);
                let key = el.attr('data-key');

                let node = $.ui.fancytree.getTree("#treeTable").getNodeByKey(key);
                let d = node.data;

                let isProject = d.type === 'project';
                let id = d.id;

                if (!el.data('daterangepicker')) {

                    let startVal = d.start_time
                        ? moment.utc(d.start_time).local()
                        : moment();

                    let endVal = d.end_time
                        ? moment.utc(d.end_time).local()
                        : moment();

                    el.daterangepicker({
                        startDate: startVal,
                        endDate: endVal,
                        opens: 'left',
                        timePicker: true,
                        timePicker24Hour: true,
                        locale: {format: 'YYYY-MM-DD HH:mm'}
                    });
                    el.data('daterangepicker').show();
                    el.on('apply.daterangepicker', function (ev, picker) {
                        if (isProject) {
                            $.post("{{ route('project.updateDates') }}", {
                                _token: "{{ csrf_token() }}",
                                project_id: id,
                                start_time: picker.startDate.utc().format('YYYY-MM-DD HH:mm:ss'),
                                end_time: picker.endDate.utc().format('YYYY-MM-DD HH:mm:ss')
                            }, async function () {
                                await refreshTreeFunction();
                            });
                        } else {
                            $.post("{{ route('project.child.updateDates') }}", {
                                _token: "{{ csrf_token() }}",
                                layer_id: id,
                                start_time: picker.startDate.utc().format('YYYY-MM-DD HH:mm:ss'),
                                end_time: picker.endDate.utc().format('YYYY-MM-DD HH:mm:ss')
                            }, async function () {
                                await refreshTreeFunction();
                            });
                        }
                    });
                } else {
                    el.data('daterangepicker').show();
                }
            });

        });
    </script>

    <script>
        $(document).on('click', '.fancytree-title', async function (e) {
            e.stopPropagation();

            const node = $.ui.fancytree.getNode(this);
            if (!node) return;

            const d = node.data;

            try {
                if (d.type === 'project') {
                    await openProjectDetails(d.id);   // or d.project_id depending on your data
                } else {
                    await openTaskDetails(d.id);
                }
            } catch (err) {
                console.error('Failed to open details modal', err);
            }
        });

        document.getElementById('closeDetailsModal').addEventListener('click', () => {
            document.getElementById('taskDetailsModal').style.display = 'none';
        });

        document.getElementById('closeProjectDetailsModal').addEventListener('click', () => {
            document.getElementById('projectDetailsModal').style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            const taskModal = document.getElementById('taskDetailsModal');
            const projectModal = document.getElementById('projectDetailsModal');

            if (e.target === taskModal) {
                taskModal.style.display = 'none';
            }

            if (e.target === projectModal) {
                projectModal.style.display = 'none';
            }
        });

        document.addEventListener('click', function (e) {
            const menu = document.getElementById('projectStatusDropdownMenu');
            const toggle = document.getElementById('projectDropdownToggle');

            if (!menu || !toggle) return;

            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        async function openProjectDetails(projectId) {
            try {
                const res = await fetch(`/board/projects/${projectId}`);
                const data = await res.json();

                await renderProjectDetails(data.project);

                // close other modal
                document.getElementById('taskDetailsModal').style.display = 'none';

                // open this modal
                document.getElementById('projectDetailsModal').style.display = 'block';

            } catch (err) {
                console.error('Failed to load project details', err);
            }
        }

        async function openTaskDetails(taskId) {
            try {
                const res = await fetch(`/board/layers/${taskId}`);
                const data = await res.json();

                // console.log(data)

                renderTaskDetails(data);

                document.getElementById('taskDetailsModal').style.display = 'block';

            } catch (err) {
                console.error('Failed to load task details', err);
            }
        }

        async function renderProjectDetails(project) {

            window.currentProjectId = project.id;

            // ======================
            // TITLE
            // ======================
            const titleEl = document.getElementById('projectDetailsName');
            const updateBtn = document.getElementById('updateProjectNameBtn');

            titleEl.value = project.title || '';
            titleEl.dataset.original = project.title || '';
            updateBtn.style.display = 'none';

            titleEl.oninput = function () {
                const current = this.value.trim();
                const original = this.dataset.original;

                updateBtn.style.display =
                    (current && current !== original) ? 'inline-flex' : 'none';
            };

            updateBtn.onclick = async function () {
                const title = titleEl.value.trim();
                if (!title) return;

                await updateProject(project.id, {title}, {
                    refreshDetails: true,
                    refreshTree: true
                });
                updateBtn.style.display = 'none';
            };

            // ======================
            // STATUS
            // ======================
            const btn = document.getElementById('projectCurrentStatusBtn');
            const menu = document.getElementById('projectStatusDropdownMenu');
            const toggle = document.getElementById('projectDropdownToggle');

            const current = window.allStatuses.find(s => s.id === project.status_id);

            btn.textContent = current?.label || 'Status';
            btn.style.background = current?.color || '#999';
            toggle.style.background = current?.color || '#999';

            menu.innerHTML = window.allStatuses.map(s => `
        <li>
            <a class="dropdown-item project-status-item"
               data-id="${s.id}"
               style="color:${s.color}">
                ${s.label}
            </a>
        </li>
    `).join('');

            toggle.onclick = (e) => {
                e.stopPropagation();
                menu.classList.toggle('show');
            };

            menu.querySelectorAll('.project-status-item').forEach(el => {
                el.onclick = async () => {
                    const statusId = parseInt(el.dataset.id);

                    menu.classList.remove('show');

                    await updateProject(project.id, {status_id: statusId}, {
                        refreshDetails: true,
                        refreshTree: true
                    });
                };
            });

            // ======================
            // DATE RANGE
            // ======================
            $('#projectDateRange')
                .off()
                .daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    startDate: project.start_time
                        ? moment.utc(project.start_time).local()
                        : moment(),

                    endDate: project.end_time
                        ? moment.utc(project.end_time).local()
                        : moment(),
                    locale: {format: 'MMM D, YYYY HH:mm'}
                })
                .on('apply.daterangepicker', async function (ev, picker) {

                    await updateProject(project.id, {
                        start_time: picker.startDate.format('YYYY-MM-DD HH:mm:ss'),
                        end_time: picker.endDate.format('YYYY-MM-DD HH:mm:ss')
                    }, {
                        refreshDetails: false,
                        refreshTree: true
                    });
                });

            // ======================
            // MANAGER
            // ======================
            const select = document.getElementById('projectManagerSelect');

            select.innerHTML = window.allUsers.map(u => `
        <option value="${u.id}">${u.name}</option>
    `).join('');

            select.value = project.user_id || '';

            select.onchange = async function () {
                await updateProject(project.id, {
                    user_id: parseInt(this.value)
                }, {
                    refreshDetails: true,
                    refreshTree: true
                });
            };

            // ======================
            // DESCRIPTION
            // ======================
            const viewEl = document.getElementById('projectDescription');
            const editorEl = document.getElementById('projectDescriptionEditor');
            const btnUpdate = document.getElementById('updateProjectDescriptionBtn');

            viewEl.innerHTML = project.description || '<i>No description</i>';
            viewEl.style.display = 'block';
            btnUpdate.style.display = 'none';

            viewEl.onclick = async function () {

                viewEl.style.display = 'none';
                btnUpdate.style.display = 'inline-flex';

                if (!window.projectDescriptionEditorInstance) {
                    window.projectDescriptionEditorInstance =
                        await ClassicEditor.create(editorEl);
                }

                window.projectDescriptionEditorInstance.setData(project.description || '');
                window.projectDescriptionEditorInstance.ui.view.element.style.display = 'block';
            };

            btnUpdate.onclick = async function () {

                const data = window.projectDescriptionEditorInstance.getData();

                await updateProject(project.id, {description: data}, {
                    refreshDetails: true,
                    refreshTree: false
                });

                viewEl.style.display = 'block';
                btnUpdate.style.display = 'none';
                window.projectDescriptionEditorInstance.ui.view.element.style.display = 'none';
            };

            // ======================
            // ACTIVITY LOG (optional)
            // ======================
            const logEl = document.getElementById('projectDetailsLog');

            if (logEl) {
                logEl.innerHTML = (project.logs || []).map(l => `
            <div>${l}</div>
        `).join('');
            }
        }

        function renderTaskDetails(data) {
            const layer = data.layer;

            window.currentLayer = layer;
            window.currentLayerId = layer.id;

            // ======================
            // TITLE
            // ======================
            const titleEl = document.getElementById('detailsName');
            const updateBtn = document.getElementById('updateLayerNameBtn');

            titleEl.value = layer.name;
            titleEl.dataset.original = layer.name;
            updateBtn.style.display = 'none';

            titleEl.oninput = function () {
                const current = this.value.trim();
                const original = this.dataset.original;

                updateBtn.style.display =
                    (current && current !== original) ? 'inline-flex' : 'none';
            };

            updateBtn.onclick = async function () {
                const name = titleEl.value.trim();
                if (!name) return;

                await updateLayer(layer.id, {name}, {
                    refreshDetails: false,
                    refreshTree: true
                });

                titleEl.dataset.original = name;
                updateBtn.style.display = 'none';
            };

            // ======================
            // STATUS
            // ======================
            const btn = document.getElementById('currentStatusBtn');
            const menu = document.getElementById('statusDropdownMenu');
            const btnDropdown = document.getElementById('dropdown-toggle-split');

            const current = window.allStatuses.find(s => s.id === layer.status_id);

            btn.textContent = current?.label || 'Status';
            btn.style.background = current?.color || '#999';
            btnDropdown.style.background = current?.color || '#999';
            btnDropdown.style.filter = 'brightness(0.85)';

            menu.innerHTML = window.allStatuses.map(s => `
        <li>
            <a class="dropdown-item status-item"
               data-id="${s.id}"
               style="color:${s.color}">
                ${s.label}
            </a>
        </li>
    `).join('');

            menu.querySelectorAll('.status-item').forEach(el => {
                el.onclick = () => {
                    const statusId = parseInt(el.dataset.id);
                    updateStatus(layer.id, statusId);
                };
            });

            // ======================
            // DATE
            // ======================
            const el = $('#detailsDateRange');

            if (el.data('daterangepicker')) {
                el.data('daterangepicker').remove();
                el.removeData('daterangepicker');
            }

            el.off() // 🔥 prevent duplicate binding
                .daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    startDate: layer.start_time
                        ? moment(layer.start_time)
                        : moment().startOf('day'),

                    endDate: layer.end_time
                        ? moment(layer.end_time)
                        : moment().startOf('day'),
                    locale: {format: 'MMM D, YYYY HH:mm'}
                })
                .on('apply.daterangepicker', async function (ev, picker) {

                    await updateLayer(layer.id, {
                        start_time: picker.startDate.utc().format('YYYY-MM-DD HH:mm:ss'),
                        end_time: picker.endDate.utc().format('YYYY-MM-DD HH:mm:ss')
                    }, {
                        refreshDetails: false,
                        refreshTree: true
                    });
                });

            // ======================
            // PROGRESS
            // ======================
            const progress = layer.progress_percent || 0;

            document.getElementById('detailsProgressBar').style.width = progress + '%';
            document.getElementById('detailsProgressText').textContent = progress + '%';

            document.getElementById('detailsSubtasks').textContent =
                `${layer.completed_tasks}/${layer.total_tasks}`;

            document.getElementById('detailsSubtaskIcon').className =
                (layer.total_tasks > 0 && layer.completed_tasks === layer.total_tasks)
                    ? 'fas fa-check-square'
                    : 'far fa-square';

            // ======================
            // USERS
            // ======================
            const usersEl = document.getElementById('detailsUsers');

            usersEl.innerHTML = layer.users.map(u => `
        <div class="user-row" data-id="${u.id}">
            <img src="https://i.pravatar.cc/32?u=${u.id}">
            <span>${u.name}</span>
            <button class="user-remove">&times;</button>
        </div>
    `).join('');

            usersEl.querySelectorAll('.user-remove').forEach(btn => {
                btn.onclick = async function () {
                    const userId = parseInt(this.closest('.user-row').dataset.id);

                    const updated = layer.users
                        .filter(u => u.id !== userId)
                        .map(u => u.id);

                    await updateLayer(layer.id, {users: updated});
                };
            });

            // select2
            const select = $('#detailsUsersSelect');

            if (select.hasClass("select2-hidden-accessible")) {
                select.select2('destroy');
            }

            select.off().empty();

            window.allUsers.forEach(u => {
                select.append(new Option(u.name, u.id));
            });

            select.val(layer.users.map(u => u.id));

            select.select2({
                placeholder: "Add users",
                width: '100%'
            });

            select.on('change', async function () {
                const selected = ($(this).val() || []).map(id => parseInt(id));

                await updateLayer(layer.id, {users: selected});
            });

            // ======================
            // DESCRIPTION
            // ======================
            const viewEl = document.getElementById('detailsDescription');
            const editorEl = document.getElementById('detailsDescriptionEditor');
            const btnUpdate = document.getElementById('updateDescriptionBtn');

            viewEl.innerHTML = layer.description || '<i>No description</i>';
            viewEl.style.display = 'block';
            btnUpdate.style.display = 'none';

            viewEl.onclick = async function () {

                viewEl.style.display = 'none';
                btnUpdate.style.display = 'inline-flex';

                if (!window.descriptionEditorInstance) {
                    window.descriptionEditorInstance = await ClassicEditor.create(editorEl);
                }

                window.descriptionEditorInstance.setData(layer.description || '');
                window.descriptionEditorInstance.ui.view.element.style.display = 'block';
            };

            btnUpdate.onclick = async function () {

                const data = window.descriptionEditorInstance.getData();

                await updateLayer(layer.id, {description: data});

                viewEl.innerHTML = data || '<i>No description</i>';
                viewEl.style.display = 'block';
                btnUpdate.style.display = 'none';
                window.descriptionEditorInstance.ui.view.element.style.display = 'none';
            };
        }

        async function updateProject(projectId, payload, options = {}) {

            const {
                refreshDetails = false,
                refreshTree = false
            } = options;

            try {
                const res = await fetch(`/projects/update/${projectId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    throw new Error(`Request failed: ${res.status}`);
                }

                showToast('Updated successfully', 'success');

                if (refreshDetails) {
                    await openProjectDetails(projectId);
                }

                if (refreshTree) {
                    await refreshTreeFunction();
                }

                return await res.json();

            } catch (e) {
                showToast('Project update failed', 'error');
                console.error('Project update failed:', e);
                throw e;
            }
        }

        async function updateLayer(layerId, payload, options = {}) {
            const {
                refreshDetails = true,
                refreshTree = false
            } = options;

            try {
                const res = await fetch(`/board/layers/${layerId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    throw new Error(`Request failed: ${res.status}`);
                }

                showToast('Updated successfully', 'success');

                // 🔁 optional refresh behaviors
                if (refreshDetails) {
                    await openTaskDetails(layerId);
                }

                if (refreshTree) {
                    await refreshTreeFunction();
                }

                return await res.json(); // useful if backend returns updated layer

            } catch (e) {
                showToast('Update Failed. Something went wrong', 'error');
                console.error('Layer update failed:', e);
                throw e; // allow caller to handle if needed
            }
        }

        async function updateStatus(layer_id, status_id) {
            try {
                await fetch(`/update-layer-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({layer_id, status_id})
                });

                // reload board
                await refreshTreeFunction();
                await openTaskDetails(layer_id)

            } catch (err) {
                console.error('Failed to update status', err);
            }
        }
    </script>

    <script>
        $(document).on('click', '.delete-project', async function (e) {
            e.stopPropagation();

            const id = $(this).data('id');

            const result = await Swal.fire({
                title: 'Delete project?',
                text: 'This will remove the project and related data.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/projects/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                });

                if (!res.ok) throw new Error(res.status);

                await Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Project deleted successfully',
                    timer: 1200,
                    showConfirmButton: false
                });

                await refreshTreeFunction();

            } catch (err) {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Could not delete project'
                });
            }
        });

        $(document).on('click', '.delete-layer', async function (e) {
            e.stopPropagation();

            const id = $(this).data('id');

            const result = await Swal.fire({
                title: 'Delete layer?',
                text: 'This will remove the layer and its children.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`/project/child/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                });

                if (!res.ok) throw new Error(res.status);

                await Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Layer deleted successfully',
                    timer: 1200,
                    showConfirmButton: false
                });

                await refreshTreeFunction();

            } catch (err) {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Could not delete layer'
                });
            }
        });

        $(document).on('click', '.open-add-modal', function (e) {
            e.stopPropagation();

            const projectId = $(this).data('project');
            const parentKey = $(this).data('parent'); // l12 or empty

            // store
            window.addLayerContext = {
                projectId,
                parentKey
            };

            $('#addLayerProjectId').val(projectId);

            let parentId = null;

            if (parentKey && parentKey.startsWith('l')) {
                parentId = parentKey.replace('l', '');
            }

            $('#addLayerParentId').val(parentId || '');

            $('#addLayerStatus').val(window.allStatuses[0]?.id).trigger('change');

            // open modal
            $('#addLayerModal').show();
        });

        $('#addLayerUsers').select2({
            placeholder: "Assign users",
            width: '100%'
        });

        $('#addLayerStatus').select2({
            placeholder: "Select status",
            width: '100%'
        });

        $('#addLayerDateRange').daterangepicker({
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,

            startDate: moment().startOf('day'),  // ✅ 00:00
            endDate: moment().startOf('day'),    // ✅ 00:00

            locale: {
                format: 'MMM D, YYYY HH:mm',
                cancelLabel: 'Clear'
            }
        });

        $('#addLayerDateRange').on('cancel.daterangepicker', function (ev, picker) {

            // clear UI
            $(this).val('');

            // clear stored values
            $(this).removeData('start').removeData('end');

            // 🔥 CRITICAL FIX: reset picker state
            picker.setStartDate(moment().startOf('day'));
            picker.setEndDate(moment().startOf('day'));
        });

        $('#addLayerDateRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(
                picker.startDate.format('MMM D, YYYY HH:mm') +
                ' → ' +
                picker.endDate.format('MMM D, YYYY HH:mm')
            );

            $(this).data('start', picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            $(this).data('end', picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
        });

        $('#closeAddLayerModal').on('click', function () {
            $('#addLayerModal').hide();
        });

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('addLayerModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        $('#addLayerForm').on('submit', async function (e) {
            e.preventDefault();

            const name = $('#addLayerName').val();
            const project_id = $('#addLayerProjectId').val();
            const parent_id = $('#addLayerParentId').val();
            const status_id = $('#addLayerStatus').val();
            const users = $('#addLayerUsers').val();

            const start_time = $('#addLayerDateRange').data('start');
            const end_time = $('#addLayerDateRange').data('end');

            if (!name || !status_id || !project_id) {
                alert('Required fields missing');
                return;
            }

            try {
                const res = await fetch("{{ route('project.child.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        name,
                        project_id,
                        parent_id,
                        status_id,
                        user_ids: users,
                        start_time,
                        end_time
                    })
                });

                const data = await res.json();

                if (data.status !== 'success') {
                    throw new Error(data.message || 'Failed');
                }

                // ✅ CLOSE MODAL
                $('#addLayerModal').hide();

                // ✅ RESET FORM
                resetAddLayerForm();

                // ✅ REFRESH TREE (CRITICAL)
                await refreshTreeFunction();

            } catch (err) {
                console.error(err);
                alert('Failed to create layer');
            }
        });

        function resetAddLayerForm() {

            $('#addLayerForm')[0].reset();

            $('#addLayerUsers').val(null).trigger('change');
            $('#addLayerParentId').val('');
            $('#addLayerStatus').val(null).trigger('change');

            $('#addLayerDateRange').val('');
            $('#addLayerDateRange').removeData('start').removeData('end');
        }
    </script>

    <script>
        $('#addProjectStatus').select2({
            width: '100%'
        });

        $('#addProjectUser').select2({
            width: '100%',
            placeholder: 'Select manager'
        });

        $('#addProjectDateRange').daterangepicker({
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            startDate: moment().startOf('day'),
            endDate: moment().startOf('day'),
            locale: {
                format: 'MMM D, YYYY HH:mm',
                cancelLabel: 'Clear'
            }
        });

        $('#addProjectDateRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(
                picker.startDate.format('MMM D, YYYY HH:mm') +
                ' → ' +
                picker.endDate.format('MMM D, YYYY HH:mm')
            );

            $(this).data('start', picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            $(this).data('end', picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
        });

        $('#addProjectDateRange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $(this).removeData('start').removeData('end');

            picker.setStartDate(moment().startOf('day'));
            picker.setEndDate(moment().startOf('day'));
        });

        $('#openAddProjectModal').on('click', function () {

            // default selections
            $('#addProjectStatus').val(window.allStatuses[0]?.id).trigger('change');
            $('#addProjectUser').val(null).trigger('change');

            $('#addProjectModal').show();
        });

        $('#closeAddProjectModal').on('click', function () {
            $('#addProjectModal').hide();
        });

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('addProjectModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        $('#addProjectForm').on('submit', async function (e) {
            e.preventDefault();

            const title = $('#addProjectTitle').val();
            const status_id = $('#addProjectStatus').val();
            const user_id = $('#addProjectUser').val();

            const start_time = $('#addProjectDateRange').data('start') || null;
            const end_time = $('#addProjectDateRange').data('end') || null;

            if (!title || !status_id || !user_id) {
                alert('Required fields missing');
                return;
            }

            try {
                const res = await fetch("{{ route('project.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        title,
                        status_id,
                        user_id,
                        start_time,
                        end_time
                    })
                });

                const data = await res.json();

                if (data.status !== 'success') {
                    throw new Error(data.message || 'Failed');
                }

                $('#addProjectModal').hide();

                resetAddProjectForm();

                await refreshTreeFunction();

            } catch (err) {
                console.error(err);
                alert('Failed to create project');
            }
        });

        function resetAddProjectForm() {

            $('#addProjectForm')[0].reset();

            $('#addProjectStatus').val(null).trigger('change');
            $('#addProjectUser').val(null).trigger('change');

            $('#addProjectDateRange').val('');
            $('#addProjectDateRange').removeData('start').removeData('end');
        }
    </script>

@endpush