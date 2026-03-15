@extends('layouts.backend.app')

@section('admin_content')

    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-xl-10 mx-auto">

                    <h6 class="mb-0 text-uppercase">Edit Layer Type</h6>
                    <hr/>

                    <div class="card border-top border-0 border-4 border-danger">
                        <div class="card-body p-5">

                            {{--                            <div class="card-title d-flex align-items-center">--}}
                            {{--                                <div><i class="bx bx-layer me-1 font-22"></i></div>--}}
                            {{--                                <h5 class="mb-0">{{$parent? 'Add Sub Layer to: ' : 'Add Layer to Project: '}}--}}
                            {{--                                    <span class="text-danger">{{$parent ? $parent->name : $project->title}}</span></h5>--}}
                            {{--                            </div>--}}

                            <hr>

                            <form class="row g-4" method="POST" action="{{ route('layerType.update', $layerType->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Layer Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Layer Type Title</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
                                        <input
                                                type="text"
                                                name="title"
                                                value="{{old('title', $layerType->title)}}"
                                                class="form-control border-start-0"
                                                placeholder="Phase 1, Module A..."
                                                required>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3">
                                    <div id="status-wrapper">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1" {{ old('status', $layerType->status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $layerType->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-danger px-5">Update Layer type</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">Back</a>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection