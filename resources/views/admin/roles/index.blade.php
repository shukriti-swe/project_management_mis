@extends('layouts.backend.app')

@section('admin_content')

<div class="page-wrapper">
    <div class="page-content">
        <div class="container mt-4">

            <h4>Role List</h4>

            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">
                Create Role
            </a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-success">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('roles.edit',$role->id) }}"
                            class="btn btn-warning btn-sm">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection