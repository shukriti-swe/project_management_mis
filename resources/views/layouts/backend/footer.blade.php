<div id="toast-container"
     class="toast-container position-fixed bottom-0 end-0 p-3">
</div>

</div>
<!--end switcher-->
<!-- Bootstrap JS -->
<script src="{{asset('admin_assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{asset('admin_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{asset('admin_assets/plugins/chartjs/js/Chart.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('admin_assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/jquery-knob/excanvas.js')}}"></script>
<script src="{{asset('admin_assets/plugins/jquery-knob/jquery.knob.js')}}"></script>
<script>
    $(function () {
        $(".knob").knob();
    });
</script>
<script src="{{asset('admin_assets/js/index.js')}}"></script>
<!-- <script src="{{ asset('admin_assets/js/custom-layer.js') }}"></script> -->
<script src="{{asset('admin_assets/js/app.js')}}"></script>
<script src="{{asset('admin_assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>

<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.ui.widget.js')}}"></script>
<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.fileupload.js')}}"></script>
<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.iframe-transport.js')}}"></script>
<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js')}}"></script>
<script src="{{asset('admin_assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{asset('admin_assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>
<script>
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
    $('.multiple-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
</script>

<script>
    function showToast(message, type = 'success') {

        const container = document.getElementById('toast-container');

        const toastEl = document.createElement('div');

        toastEl.className = `toast align-items-center text-white border-0 ${
            type === 'success' ? 'bg-success' : 'bg-danger'
        }`;

        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    `;

        container.appendChild(toastEl);

        const toast = new bootstrap.Toast(toastEl, {
            delay: 3000
        });

        toast.show();

        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }
</script>
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>
<script>
    $(document).ready(function () {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
    $('#fancy-file-upload').FancyFileUpload({
        params: {
            action: 'fileuploader'
        },
        maxfilesize: 1000000
    });
</script>

<script>
    $(document).ready(function () {
        $('#image-uploadify').imageuploadify();
    })
</script>

<script>
    ClassicEditor.create(document.querySelector('.editor'), {
        ckfinder: {
            uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
        }
    })
        .catch(error => {
        });
</script>


</body>

</html>