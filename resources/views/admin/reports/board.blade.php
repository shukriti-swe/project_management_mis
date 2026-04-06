@extends('layouts.backend.app')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* =========================
           VARIABLES
        ========================= */
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
           RESET / BASE
        ========================= */
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

        /* =========================
           LAYOUT
        ========================= */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px;
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

        .header-project {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 40px 0;
        }

        .header-project .label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .header-project .project-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-right: 30px;
        }

        /*Make select2 compact */
        #projectSelect + .select2-container {
            min-width: 200px;
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

        #addTaskBtn:hover, .submit-btn:hover {
            color: #FFF;
            background-color: #376dc6;
        }

        /* =========================
           BOARD
        ========================= */
        .board {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            overflow-x: auto;
        }

        /* =========================
           COLUMN
        ========================= */
        .column {
            min-width: 320px;
            /*max-width: 280px;   !* critical *!*/

            flex: 0 0 280px; /* prevents expansion */

            background: var(--column-bg);
            border: 1px solid var(--border);
            border-top: 4px solid var(--status-color, #939393);
            border-radius: var(--radius);
            padding: 8px;

            display: flex;
            flex-direction: column;
        }

        /* Header */
        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .column-header h2 {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
        }

        .column-header .right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Count */
        .task-count {
            background: var(--status-color, #e5e7eb);
            color: #FFF;
            font-size: 13px;
            padding: 2px 10px;
            border-radius: 999px;
        }

        /* Add button */
        .col-add-btn {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 16px;
            color: var(--muted);
        }

        .col-add-btn:hover, .col-add-btn:focus {
            background: #e5e7eb;
            outline: none;
        }

        /* Task List */
        .task-list {
            flex-grow: 1;
            min-height: 120px;
            padding-bottom: 80px;
            border: 1px dashed transparent;
        }

        /* Footer */
        .column-footer {
            margin-top: 6px;
        }

        .add-new {
            width: 100%;
            background: transparent;
            border: none;
            text-align: left;
            padding: 6px;
            border-radius: 6px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
        }

        .add-new:hover, .add-new:focus {
            background: #e5e7eb;
            outline: none;
        }

        /* =========================
           CARD
        ========================= */
        .task-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 10px;
            margin-bottom: 8px;
            cursor: grab;

            box-shadow: var(--shadow-sm);
            transition: 0.15s ease;
        }

        .task-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .task-card:active {
            cursor: grabbing;
        }

        .task-card.dragging {
            opacity: 0.5;
        }

        /* Title */
        .task-title {
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .task-title:hover {
            text-decoration: underline;
        }

        /* Assignee */
        .task-assignees {
            max-width: 320px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
            /*max-width: 100%;*/
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        /* Each user */
        .assignee {
            display: inline-flex; /* key change */
            align-items: center;
            gap: 4px;

            max-width: 100%;
            flex: 0 1 auto; /* allow shrink */

            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 6px;

            font-size: 11px;
        }

        .assignee span {
            display: inline-block;
            max-width: 90px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Avatar */
        .assignee img {
            width: 18px;
            height: 18px;
            border-radius: 50%;
        }

        /* =========================
           TASK DATES
        ========================= */
        .task-dates {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 11px;
        }

        /* Base date */
        .date {
            display: flex;
            align-items: center;
            gap: 4px;

            padding: 2px 6px;
            border-radius: 4px;
            background: #eef2ff;
            color: #3730a3;
        }

        /* Start date (neutral) */
        .date.start {
            background: #f1f5f9;
            color: #475569;
        }

        /* End date (highlight) */
        .date.end {
            background: #f1f5f9;
            color: #475569;
        }

        /* Arrow */
        .date-arrow {
            font-size: 11px;
            color: var(--muted);
        }

        /* =========================
           PROGRESS ROW
        ========================= */
        .task-footer {
            border-top: 1px solid var(--border);
            margin-top: 8px;
            padding-top: 8px;
        }

        .progress-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Subtasks */
        .subtasks {
            font-size: 11px;
            color: var(--muted);
            min-width: 30px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .subtasks i {
            font-size: 11px;
        }

        /* Separator */
        .separator {
            width: 1px;
            height: 12px;
            background: var(--border);
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
           DRAG STATE
        ========================= */
        .task-list.drag-over {
            background: #e0f2fe;
            border: 1px dashed #98c2d1;
            border-radius: var(--radius);
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
            width: 90%;
            max-width: 420px;
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

        .close {
            position: absolute;
            right: 12px;
            top: 10px;
            cursor: pointer;
            font-size: 18px;
            color: var(--muted);
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
    <style>
        /* =========================
           FORCE NO PAGE SCROLL
        ========================= */

        html, body {
            height: 100% !important;
            overflow: hidden !important;
        }

        /* Kill all outer wrappers */
        .page-wrapper,
        .page-content,
        .container-fluid {
            height: calc(100vh - 80px) !important;
            max-height: 100vh !important;

            display: flex !important;
            flex-direction: column !important;

            overflow: hidden !important;
        }

        .container-fluid {
            height: 100% !important;
            display: flex;
            flex-direction: column;
        }

        /* =========================
           HEADER FIX
        ========================= */

        header {
            flex: 0 0 auto !important;
            margin: 0 0 20px !important;
            padding: 10px 0 20px !important;
            border-bottom: 1px solid var(--border);
        }

        /* remove this breaking spacing */
        .header-project {
            margin: 0 !important;
        }

        /* =========================
           BOARD LAYOUT
        ========================= */

        .board {
            flex: 1 1 auto !important;

            display: flex !important;
            gap: 12px !important;

            overflow-x: auto !important;
            overflow-y: hidden !important;

            min-height: 0 !important;
        }

        .board.is-draggable {
            cursor: grab;
        }

        .board.is-draggable.grabbing {
            cursor: grabbing;
        }

        /* =========================
           COLUMN FIX
        ========================= */

        .column {
            flex: 0 0 280px !important;

            display: flex !important;
            flex-direction: column !important;

            max-height: 100% !important;
            min-height: 0 !important;
        }

        /* =========================
           ONLY SCROLL AREA
        ========================= */

        .task-list {
            flex: 1 1 auto !important;

            overflow-y: auto !important;

            min-height: 0 !important;
        }

        /*!* Chrome, Safari *!*/
        /*.task-list::-webkit-scrollbar {*/
        /*    display: none;*/
        /*}*/

        /*!* Firefox *!*/
        /*.task-list {*/
        /*    scrollbar-width: none;*/
        /*}*/

        /*!* IE/Edge legacy *!*/
        /*.task-list {*/
        /*    -ms-overflow-style: none;*/
        /*}*/
        /* Chrome, Safari */
        /* Default: hidden */
        .task-list::-webkit-scrollbar {
            width: 0;
        }

        /* On hover: show thin scrollbar */
        .task-list:hover::-webkit-scrollbar {
            width: 2px !important;
        }

        /* Track */
        .task-list::-webkit-scrollbar-track {
            background: transparent;
            margin: 4px 0;
        }

        /* Thumb */
        .task-list::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }

        /* Thumb hover */
        .task-list::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Remove arrows */
        .task-list::-webkit-scrollbar-button {
            display: none;
            height: 0;
            width: 0;
        }

        /* Firefox */
        .task-list {
            scrollbar-width: none; /* hidden by default */
        }

        /* Firefox hover (approximation) */
        .task-list:hover {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
        }
    </style>
    <style>
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
        }

        /* Title input */
        .title-input {
            font-size: 18px;
            font-weight: 600;
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
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
            gap: 30px;
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
            display: flex;
            align-items: center;
            gap: 8px;

            padding: 6px;
            border-radius: 6px;

            background: #f8fafc;
        }

        .user-row img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }
    </style>
@endpush

@section('admin_content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <header>
                    <div class="header-project">
                        <span class="label">PROJECT</span>

                        <span id="currentProjectName" class="project-name">Modern Kanban Board</span>

                        <i class="fa-solid fa-rotate"></i> Chnage Project
                        <select id="projectSelect">
                            @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button id="addTaskBtn" class="btn addTaskBtn">
                        <i class="fas fa-plus"></i> Add Layer
                    </button>
                </header>

                <div class="board">
                    @foreach($statuses as $status)
                        <div class="column" id="{{$status->id}}" style="--status-color: {{ $status->color }}">
                            <div class="column-header">
                                <div class="left">
                                    <h2>{{ucfirst($status->label)}}</h2>
                                </div>

                                <div class="right">
                                    <span class="task-count">{{ $status->layer_count }}</span>
                                    <button class="col-add-btn addTaskBtn"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="task-list" data-column="{{ $status->id }}">
                                <!-- Tasks will be added here dynamically -->
                            </div>
                            <div class="column-footer">
                                <button class="add-new addTaskBtn">+ New</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Task Modal -->
            <div class="modal" id="taskModal">
                <div class="modal-content modal-lg">
                    <span class="close">&times;</span>

                    <h2 class="modal-title">Create Layer</h2>

                    <form id="taskForm">

                        <!-- Layer Name -->
                        <div class="form-group">
                            <label>Layer Name</label>
                            <input type="text" id="layerName" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group" id="statusField">
                            <label>Status</label>
                            <select id="statusSelect">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}">{{$status->label}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assign Users -->
                        <div class="form-group">
                            <label>Assign Users</label>
                            <select id="usersSelect" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Parent Layer -->
                        <div class="form-group">
                            <label>Parent Layer</label>
                            <select id="parentLayerSelect">
                                <option value="">None</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>

                        <!-- Dates -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date Range</label>
                                <input type="text" id="dateRange" placeholder="Select date range" readonly>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="taskDescription" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn submit-btn">
                            <i class="fas fa-check"></i> Create Layer
                        </button>
                    </form>
                </div>
            </div>

            <div class="modal" id="taskDetailsModal">
                <div class="modal-content modal-xl">

                    <span class="close" id="closeDetailsModal">&times;</span>

                    <!-- HEADER -->
                    <div class="modal-header">
                        <input id="detailsName" class="title-input"/>
                    </div>

                    <!-- BODY -->
                    <div class="task-details-layout">

                        <!-- ================= LEFT ================= -->
                        <div class="details-left">

                            <div class="detail-group">
                                <label>Status</label>
                                <div class="detail-group">
{{--                                    <label>Status</label>--}}

                                    <div class="status-dropdown btn-group">
                                        <button id="currentStatusBtn" type="button" class="btn status-btn">
                                            Status
                                        </button>

                                        <button type="button"
                                                class="btn dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown">
                                        </button>

                                        <ul id="statusDropdownMenu" class="dropdown-menu"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-group">
                                <label>Progress</label>

                                <div class="progress-inline">

                                    <div class="subtasks-inline">
                                        <i id="detailsSubtaskIcon"></i> Subtasks Count:
                                        <span id="detailsSubtasks"></span>
                                    </div>

                                    <div class="progress">
                                        <div id="detailsProgressBar" class="progress-bar"></div>
                                    </div>

                                    <span id="detailsProgressText"></span>

                                </div>
                            </div>

                            <div class="detail-group">
                                <label>Date Range</label>
                                <input id="detailsDateRange" class="editable-input">
                            </div>

                            <div class="detail-group">
                                <label>Assigned Users</label>
                                <div id="detailsUsers" class="users"></div>
                            </div>

                            <div class="detail-group">
                                <label>Description</label>
                                <textarea id="detailsDescription" rows="6"></textarea>
                            </div>

                        </div>

                        <!-- ================= RIGHT ================= -->
                        <div class="details-right">

                            <!-- TREE -->
                            <div class="tree-section">
                                <div class="section-title">Structure</div>
                                <div id="detailsTree"></div>
                            </div>

                            <!-- LOG -->
                            <div class="log-section">
                                <div class="section-title">Activity</div>
                                <div id="detailsLog"></div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#projectSelect').select2({
                placeholder: "Select project",
                // width: '100%'
            });

            $('#projectSelect').on('change', function () {
                const text = $(this).find('option:selected').text();

                // update header
                $('#currentProjectName').text(text);

                // reload board
                loadBoardData();
                loadParentLayers();
            });

            $('#usersSelect').select2({
                placeholder: "Assign users",
                width: '100%'
            });

            $('#parentLayerSelect').select2({
                placeholder: "Select parent layer",
                width: '100%',
                allowClear: true
            });

            $('#statusSelect').select2({
                placeholder: "Select status",
                width: '100%'
            });

            $('#dateRange').daterangepicker({
                autoUpdateInput: false,
                opens: 'right',

                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,   // minute-level precision

                locale: {
                    format: 'MMM D, YYYY HH:mm',
                    cancelLabel: 'Clear'
                }
            });

            $('#dateRange').on('apply.daterangepicker', function (ev, picker) {

                // Display format (UI)
                $(this).val(
                    picker.startDate.format('MMM D, YYYY HH:mm') +
                    ' → ' +
                    picker.endDate.format('MMM D, YYYY HH:mm')
                );

                // Store for backend (Laravel-friendly)
                $(this).data('start', picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
                $(this).data('end', picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
            });

            $('#dateRange').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                $(this).removeData('start').removeData('end');
            });

            const start_time = $('#dateRange').data('start');
            const end_time = $('#dateRange').data('end');

        });
    </script>
    <script>
        window.allStatuses = @json($statuses);
    </script>
    <script>
        // DOM Elements
        // const addTaskBtn = document.getElementById('addTaskBtn');
        const addTaskBtn = document.querySelectorAll('.addTaskBtn');
        const taskModal = document.getElementById('taskModal');
        const closeModal = document.querySelector('.close');
        const taskForm = document.getElementById('taskForm');
        const taskLists = document.querySelectorAll('.task-list');
        let currentColumnStatus = null;

        // State
        let tasks = [];

        async function loadBoardData() {
            try {
                const projectId = $('#projectSelect').val();
                const res = await fetch(`/board/data?project_id=${projectId}`);
                const data = await res.json();

                tasks = data.layers;

                renderBoard();
            } catch (err) {
                console.error('Failed to load board data', err);
            }
        }

        let draggedTask = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadBoardData();
            loadParentLayers();
            setupEventListeners();
        });

        function renderBoard() {
            // clear all columns first
            document.querySelectorAll('.task-list').forEach(list => {
                list.innerHTML = '';
            });

            tasks.forEach(task => {
                addTaskToDOM(task);
            });
            updateTaskCounts();
        }

        // Setup Event Listeners
        function setupEventListeners() {
            // Modal Events
            addTaskBtn.forEach(btn => {
                btn.addEventListener('click', function () {

                    const column = this.closest('.column');

                    if (column) {
                        // ✅ opened from column
                        currentColumnStatus = column.id;

                        $('#statusSelect')
                            .val(currentColumnStatus)
                            .trigger('change');

                        $('#statusField').hide();

                    } else {
                        // ✅ global add
                        currentColumnStatus = null;

                        $('#statusField').show();
                        $('#statusSelect').val(null).trigger('change');
                    }

                    taskModal.style.display = 'block';
                });
            });

            closeModal.addEventListener('click', () => {
                taskModal.style.display = 'none';
            });

            window.addEventListener('click', (e) => {
                if (e.target === taskModal) {
                    taskModal.style.display = 'none';
                }
            });

            // Form Submit
            taskForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const name = $('#layerName').val();
                const status_id = currentColumnStatus
                    ? currentColumnStatus
                    : $('#statusSelect').val();
                const users = $('#usersSelect').val();
                const parent_id = $('#parentLayerSelect').val();

                const start_time = $('#dateRange').data('start');
                const end_time = $('#dateRange').data('end');
                const description = $('#taskDescription').val();

                const project_id = $('#projectSelect').val();

                if (!status_id) {
                    alert('Status is required');
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
                            status_id,
                            parent_id,
                            user_ids: users,
                            start_time,
                            end_time,
                            description
                        })
                    });

                    const data = await res.json();

                    if (data.status !== 'success') {
                        throw new Error(data.message || 'Failed');
                    }

                    // close modal
                    taskModal.style.display = 'none';

                    // reset form
                    taskForm.reset();
                    $('#usersSelect').val(null).trigger('change');
                    $('#parentLayerSelect').val(null).trigger('change');
                    $('#statusSelect').val(null).trigger('change');
                    const picker = $('#dateRange').data('daterangepicker');

                    if (picker) {
                        picker.setStartDate(moment());
                        picker.setEndDate(moment());
                    }

                    $('#dateRange').val('');
                    $('#dateRange').removeData('start').removeData('end');

                    // reload board (important)
                    await loadBoardData();

                } catch (err) {
                    console.error(err);
                    alert('Failed to create layer');
                }
            });

            // Drag and Drop Events
            taskLists.forEach(list => {
                list.addEventListener('dragover', handleDragOver);
                list.addEventListener('dragleave', handleDragLeave);
                list.addEventListener('drop', handleDrop);
            });
        }

        // Add Task to DOM
        function addTaskToDOM(task) {
            const taskList = document.querySelector(`[data-column="${task.status_id}"]`);
            if (!taskList) return;

            const taskElement = document.createElement('div');
            taskElement.className = 'task-card';
            taskElement.draggable = true;
            taskElement.id = task.id;

            const usersHTML = task.users.map(u => `
        <div class="assignee">
            <img src="https://i.pravatar.cc/24?u=${u.id}">
            <span>${u.name}</span>
        </div>
    `).join('');

            const progress = task.progress_percent || 0;
            const isComplete = task.total_tasks > 0 && task.completed_tasks === task.total_tasks;

            const iconClass = isComplete
                ? 'fas fa-check-square'     // filled
                : 'far fa-square';          // empty (or use far fa-check-square for outline)

            taskElement.innerHTML = `
        <div class="task-title">${task.name}</div>

        <div class="task-dates">
            <span class="date start">
                <i class="fas fa-calendar"></i>
                ${formatDate(task.start_time)}
            </span>
            <span class="date-arrow">→</span>
            <span class="date end">
                ${formatDate(task.end_time)}
            </span>
        </div>

        <div class="task-assignees">
            ${usersHTML}
        </div>

        <div class="task-footer">
            <div class="progress-row">

                <div class="subtasks">
                    <i class="${iconClass}"></i> Subtasks
                    <span>${task.completed_tasks}/${task.total_tasks}</span>
                </div>

                <div class="separator"></div>

                <div class="progress">
                    <div class="progress-bar" style="width: ${progress}%"></div>
                </div>

                <span class="progress-text">${progress}%</span>

            </div>
        </div>
    `;

            taskElement.addEventListener('dragstart', handleDragStart);
            taskElement.addEventListener('dragend', handleDragEnd);

            taskList.appendChild(taskElement);

            const titleEl = taskElement.querySelector('.task-title');

            titleEl.addEventListener('click', (e) => {
                e.stopPropagation();
                openTaskDetails(task.id);
            });
        }

        async function openTaskDetails(taskId) {
            try {
                const res = await fetch(`/board/layers/${taskId}`);
                const data = await res.json();

                renderTaskDetails(data);

                document.getElementById('taskDetailsModal').style.display = 'block';

            } catch (err) {
                console.error('Failed to load task details', err);
            }
        }

        document.getElementById('closeDetailsModal').addEventListener('click', () => {
            document.getElementById('taskDetailsModal').style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('taskDetailsModal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        function renderTaskDetails(data) {
            const layer = data.layer;

            // 🔥 IMPORTANT: store current id globally
            window.currentLayerId = layer.id;

            // ======================
            // TITLE (input now)
            // ======================
            const titleEl = document.getElementById('detailsName');
            if (titleEl) titleEl.value = layer.name;

            // ======================
            // STATUS (dropdown)
            // ======================
            const btn = document.getElementById('currentStatusBtn');
            const menu = document.getElementById('statusDropdownMenu');

            if (btn && menu && window.allStatuses) {

                // set current
                const current = window.allStatuses.find(s => s.id === layer.status_id);
                btn.textContent = current?.label || 'Status';
                btn.style.background = current?.color || '#ccc';

                // build menu
                menu.innerHTML = window.allStatuses.map(s => `
        <li>
            <a class="dropdown-item status-item"
               data-id="${s.id}"
               style="color:${s.color}">
                ${s.label}
            </a>
        </li>
    `).join('');

                // click
                menu.querySelectorAll('.status-item').forEach(el => {
                    el.addEventListener('click', () => {
                        updateLayerField('status_id', el.dataset.id);
                        btn.textContent = el.textContent;
                    });
                });
            }

            // ======================
            // SUBTASKS
            // ======================
            const subtasksEl = document.getElementById('detailsSubtasks');
            if (subtasksEl) {
                subtasksEl.textContent = `${layer.completed_tasks}/${layer.total_tasks}`;
            }

            const isComplete = layer.total_tasks > 0 && layer.completed_tasks === layer.total_tasks;

            document.getElementById('detailsSubtaskIcon').className =
                isComplete ? 'fas fa-check-square' : 'far fa-square';

            document.getElementById('detailsSubtasks').textContent =
                `${layer.completed_tasks}/${layer.total_tasks}`;

            // ======================
            // PROGRESS
            // ======================
            const progress = layer.progress_percent || 0;

            const bar = document.getElementById('detailsProgressBar');
            const text = document.getElementById('detailsProgressText');

            if (bar) bar.style.width = progress + '%';
            if (text) text.textContent = progress + '%';

            // ======================
            // DATE
            // ======================
            const dateEl = document.getElementById('detailsDateRange');
            if (dateEl) {
                dateEl.value = `${formatDate(layer.start_time)} → ${formatDate(layer.end_time)}`;
            }

            $('#detailsDateRange').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                autoUpdateInput: true,
                startDate: moment(layer.start_time),
                endDate: moment(layer.end_time),
                locale: {
                    format: 'MMM D, YYYY HH:mm'
                }
            }).on('apply.daterangepicker', function (ev, picker) {

                updateLayerField('dates', {
                    start: picker.startDate.format('YYYY-MM-DD HH:mm:ss'),
                    end: picker.endDate.format('YYYY-MM-DD HH:mm:ss')
                });
            });

            // ======================
            // DESCRIPTION
            // ======================
            const descEl = document.getElementById('detailsDescription');
            if (descEl) descEl.value = layer.description || '';

            // ======================
            // USERS
            // ======================
            const usersEl = document.getElementById('detailsUsers');
            if (usersEl) {
                usersEl.innerHTML = layer.users.map(u => `
    <div class="user-row">
        <img src="https://i.pravatar.cc/32?u=${u.id}">
        <span>${u.name}</span>
    </div>
`).join('');
            }

            // ======================
            // TREE
            // ======================
            if (data.tree) {
                renderTree(data.tree, layer.id);
            }

            // ======================
            // LOG (static for now)
            // ======================
            const logEl = document.getElementById('detailsLog');
            if (logEl) {
                logEl.innerHTML = `
            <div>✔ Created</div>
            <div>✔ Status changed</div>
            <div>✔ Assigned users</div>
        `;
            }
        }

        function renderTree(tree, currentId) {
            const container = document.getElementById('detailsTree'); // create this div

            const root = tree[0];

            container.innerHTML = renderNode(root, currentId);
        }

        function renderNode(node, currentId) {
            return `
        <div class="tree-node ${node.id === currentId ? 'active' : ''}">
            ${node.name}

            ${node.children?.length ? `
                <div class="tree-children">
                    ${node.children.map(child => renderNode(child, currentId)).join('')}
                </div>
            ` : ''}
        </div>
    `;
        }

        // Drag and Drop Handlers
        function handleDragStart(e) {
            draggedTask = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', this.id);
        }

        function handleDragEnd() {
            this.classList.remove('dragging');
            draggedTask = null;
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
        }

        function handleDragLeave() {
            this.classList.remove('drag-over');
        }

        async function handleDrop(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            if (!draggedTask) return;

            const newColumn = this.getAttribute('data-column');
            const taskId = draggedTask.id;

            const taskIndex = tasks.findIndex(task => task.id == taskId);
            if (taskIndex === -1) return;

            const newStatusId = parseInt(newColumn);

            // prevent unnecessary call
            if (tasks[taskIndex].status_id == newStatusId) return;

            // optimistic UI move
            this.appendChild(draggedTask);

            try {
                const res = await fetch("{{ route('updateLayerStatus') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        layer_id: taskId,
                        status_id: newStatusId
                    })
                });

                const data = await res.json();

                if (!data.success) throw new Error('Update failed');

                // 🔥 IMPORTANT: re-fetch everything
                await loadBoardData();

            } catch (err) {
                console.error(err);

                // rollback UI
                renderBoard();
            }
        }

        function formatDate(date) {
            if (!date) return '';
            return moment(date).format('MMM D');
        }

        function updateTaskCounts() {
            document.querySelectorAll('.task-list').forEach(list => {
                const columnId = list.getAttribute('data-column');
                const countEl = list.closest('.column').querySelector('.task-count');

                const count = tasks.filter(t => t.status_id == columnId).length;

                if (countEl) countEl.textContent = count;
            });
        }

        function loadParentLayers() {
            const projectId = $('#projectSelect').val();

            fetch(`/board/layers?project_id=${projectId}`)
                .then(res => res.json())
                .then(data => {
                    const select = $('#parentLayerSelect');

                    select.empty().append('<option value="">None</option>');

                    data.layers.forEach(layer => {
                        select.append(
                            new Option(layer.name, layer.id)
                        );
                    });

                    select.trigger('change');
                });
        }
    </script>
    <script>
        const board = document.querySelector('.board');

        let isDown = false;
        let startX;
        let scrollLeft;

        /* =========================
           DRAG TO SCROLL
        ========================= */
        board.addEventListener('mousedown', (e) => {

            // ❌ disable if no overflow
            if (!board.classList.contains('is-draggable')) return;

            // ❌ ignore task drag
            if (e.target.closest('.task-card')) return;

            isDown = true;
            board.classList.add('grabbing');

            startX = e.pageX - board.offsetLeft;
            scrollLeft = board.scrollLeft;
        });

        board.addEventListener('mouseleave', () => {
            isDown = false;
            board.classList.remove('grabbing');
        });

        board.addEventListener('mouseup', () => {
            isDown = false;
            board.classList.remove('grabbing');
        });

        board.addEventListener('mousemove', (e) => {
            if (!isDown) return;

            e.preventDefault();

            const x = e.pageX - board.offsetLeft;
            const walk = (x - startX) * 1.5;

            board.scrollLeft = scrollLeft - walk;
        });


        /* =========================
           OVERFLOW DETECTION
        ========================= */
        function updateBoardCursor() {
            if (!board) return;

            const isOverflowing = board.scrollWidth > board.clientWidth;

            if (isOverflowing) {
                board.classList.add('is-draggable');
            } else {
                board.classList.remove('is-draggable');
            }
        }


        /* =========================
           RESIZE OBSERVER (KEY FIX)
        ========================= */
        const observer = new ResizeObserver(() => {
            updateBoardCursor();
        });

        observer.observe(board);


        /* =========================
           INITIAL RUN
        ========================= */
        window.addEventListener('load', updateBoardCursor);
    </script>
@endpush