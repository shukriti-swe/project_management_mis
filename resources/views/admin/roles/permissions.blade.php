@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container mt-4">

            <h4 class="mb-4">Permission Management</h4>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Error --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Create Permission --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Enter permission name (ex: project.create)"
                                       required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Add Permission
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Permission List --}}
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Permission Name</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td style="display: flex;gap:5px;">
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('permissions.delete', $permission->id) }}" onsubmit="return confirm('Delete this permission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        No permissions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection