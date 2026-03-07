@extends('layouts.backend.app')

@section('admin_content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container mt-4">

            <h4 class="mb-4">Edit Permission</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('permissions.update', $permission->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Permission Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ $permission->name }}"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit"
                                class="btn btn-primary">
                            Update Permission
                        </button>

                        <a href="{{ route('permissions.index') }}"
                           class="btn btn-secondary">
                            Back
                        </a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection