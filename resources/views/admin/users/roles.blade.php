@extends('layouts.backend.app')

@section('admin_content')

<div class="page-wrapper">
    <div class="page-content">
        <div class="container mt-4">

            <h4>Assign Role to {{ $user->name }}</h4>

            <form method="POST" action="{{ route('users.roles.update',$user->id) }}">
                @csrf

                @foreach($roles as $role)
                    <div class="form-check">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="form-check-input"{{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $role->name }}</label>
                    </div>
                @endforeach

                <button class="btn btn-primary mt-3">Update Roles</button>
            </form>
        </div>
    </div>
</div>

@endsection