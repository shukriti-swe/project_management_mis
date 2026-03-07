@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container mt-4">

            <h4>Edit Role</h4>

            <form method="POST" action="{{ route('roles.update',$role->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Role Name</label>
                    <input type="text"
                        name="name"
                        value="{{ $role->name }}"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Assign Permissions</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    class="form-check-input"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection