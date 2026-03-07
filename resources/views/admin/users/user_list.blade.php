@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
<div class="page-content">
<div class="container mt-4">

<h4>User List</h4>

<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
    Add User
</a>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $key => $user)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @foreach($user->getRoleNames() as $role)
                    <span class="badge bg-success">{{ $role }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('users.edit',$user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form method="POST" action="{{ route('users.delete',$user->id) }}" style="display:inline;" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>
</div>
@endsection