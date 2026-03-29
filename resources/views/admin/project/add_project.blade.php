@extends('layouts.backend.app')

@section('admin_content')

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-7 mx-auto">

                <h6 class="mb-0 text-uppercase">Project</h6>
                <hr/>
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-danger"></i>
                            </div>
                            <h5 class="mb-0 text-danger">Add Project</h5>
                        </div>
                        <hr>
                        
                        <form class="row g-3" method="POST" action="{{ route('saveProject') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <label for="inputTitle" class="form-label">Project Title</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bxs-user' ></i></span>
                                    <input type="text" name="title" class="form-control border-start-0" id="inputTitle" placeholder="Title" />
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Description</label>
                                <textarea  class="form-control editor" name="description" ></textarea>
                                
                            </div>

                            <div class="col-12">
                                <label for="inputDate" class="form-label">Start Date</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bxs-user' ></i></span>
                                    <input type="date" name="start_date" class="form-control border-start-0" id="inputDate" placeholder="date" />
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="inputDate" class="form-label">End Date</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bxs-user' ></i></span>
                                    <input type="date" name="end_date" class="form-control border-start-0" id="inputDate" placeholder="date"/>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="inputStatus" class="form-label">Status</label>
                                <div class="input-group"><span class="input-group-text bg-transparent"><i class='bx bxs-user' ></i></span>
                                    <select id="inputStatus" class="form-select" name="status">
                                        <option selected>-- Select one --</option>
                                        <option value="1">Not start</option>
                                        <option value="2">Running</option>
                                        <option value="3">Pause</option>
                                        <option value="4">End</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="image-uploadify" class="form-label">File</label>
                                <div class="card">
                                    <div class="card-body">
                                        <input id="image-uploadify2222" type="file" name="image" accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf" multiple>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger px-5">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->

<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
<footer class="page-footer">
    <p class="mb-0">Copyright © 2021. All right reserved.</p>
</footer>


@endsection

