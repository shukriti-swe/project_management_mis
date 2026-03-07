@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
<div class="page-content">
<div class="container mt-4">

<h4>Create User</h4>

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('users.store') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Assign Roles</label>
        @foreach($roles as $role)
            <div class="form-check">
                <input type="checkbox"
                       name="roles[]"
                       value="{{ $role->name }}"
                       class="form-check-input">
                <label class="form-check-label">
                    {{ $role->name }}
                </label>
            </div>
        @endforeach
    </div>

    <button class="btn btn-primary">Create User</button>

</form>

</div>
</div>
</div>
@endsection