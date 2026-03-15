@extends('layouts.backend.app')

@section('admin_content')

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

                    

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Filter:</label>
                            <select id="statusFilter" class="form-select" style="width: 200px; display: inline-block;">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="In-Active">In-Active</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6" style="text-align:right;">
                            <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLayerModal">Add Layer</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered table-hover" style="width:100%">
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
                            @foreach($layers as $layer)
                                <tr>
                                    <td>{{ $layer->id }}</td>
                                    <td>{{ $layer->name }}</td>
                                    <td>{{ $layer->start_time }}</td>
                                    <td>{{ $layer->start_time }}</td>
                                    <td>{{ $layer->duration }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary update-status-btn" 
                                                data-id="{{ $layer->id }}" data-index="{{ $layer->project_id }}"
                                                data-current-status="{{ $layer->status_id == 1 ? 'Active' : 'In-Active' }}">
                                            {{ $layer->status_id == 1 ? 'Active' : 'In-Active' }} <i class="bx bx-edit-alt"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="{{ route('editProject',$layer->id) }}" class="btn btn-warning">Edit</a>
                                            <a href="{{ route('deleteProject',$layer->id) }}" class="btn btn-danger">Delete</a>
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

    <div class="modal fade" id="addLayerModal" tabindex="-1" aria-labelledby="addLayerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLayerModalLabel">Add New Layer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addLayerForm" method="POST" action="{{ route('storeLayer') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Layer Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Authentication Layer" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Project <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-select single-select-no-parent" required>
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Parent Layer</label>
                            <select name="parent_layer_id" class="form-select single-select">
                                <option value="">-- No Parent (This is a top layer) --</option>
                                @foreach($layers as $l)
                                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Write layer details here..."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Layer Type <span class="text-danger">*</span></label>
                            <select name="layer_type_id" id="layerTypeSelect" class="form-select" required>
                                <option value="">-- Select or type new --</option>
                                @foreach($layerTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Assigned Users</label>
                            <select name="assigned_user_ids[]" id="assignedUsers" class="form-select multiple-select" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Duration (days)</label>
                            <input type="number" name="duration" class="form-control" min="1" placeholder="Auto-calculate if empty">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status_id" class="form-select single-select" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveLayerBtn">
                        <i class="bx bx-save me-1"></i> Save Layer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Layer Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusUpdateForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="layer_id" name="layer_id">
                        <input type="hidden" id="project_id" name="project_id">
                        <div class="mb-3">
                            <label class="form-label">Status Title</label>
                            <input type="text" class="form-control" id="status_title" name="title" placeholder="e.g. Completed, Pending, On Hold" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="page-footer">
        <p class="mb-0">Copyright © 2021. All right reserved.</p>
    </footer>


<script>
    $(document).ready(function() {
    
    // ১. Select2 ইনিশিয়ালাইজেশন (মোডাল ড্রপডাউন ইস্যু ফিক্সড)
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
                return { id: term, text: term, newTag: true };
            }
        });
    }

    // মোডাল খোলার পর Select2 লোড করা (পজিশন ঠিক রাখার জন্য)
    $('#addLayerModal').on('shown.bs.modal', function () {
        $('.select2-init').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#addLayerModal'), // এটি অত্যন্ত জরুরি
            placeholder: "Select an option"
        });
    });

    // ২. নতুন Layer Type অটো সেভ (Select2 tags)
    $('#layerTypeSelect').on('select2:select', function(e) {
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
                success: function(response) {
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

    // ৩. ফর্ম সাবমিট (AJAX) - লোডিং স্পিনার ছাড়া শুধু এলার্ট
    $('#addLayerForm').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $('#saveLayerBtn');

        $btn.prop('disabled', true); // ডাবল ক্লিক রোধ করতে

        $.ajax({
            url: $form.attr('action'),
            method: "POST",
            data: $form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alertify.success('Layer saved successfully!');
                    $('#addLayerModal').modal('hide');
                    $form[0].reset();
                    
                    // ১.৫ সেকেন্ড পর পেজ রিলোড হবে যাতে মেসেজ দেখা যায়
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false); // ভুল হলে বাটন আবার সচল হবে
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



@endsection

