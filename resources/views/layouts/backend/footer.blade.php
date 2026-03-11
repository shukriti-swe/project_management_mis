</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{asset('admin_assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->

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
        $(function() {
            $(".knob").knob();
        });
    </script>
    <script src="{{asset('admin_assets/js/index.js')}}"></script>
	<script src="{{asset('admin_assets/js/app.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>

	<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.ui.widget.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.fileupload.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.iframe-transport.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js')}}"></script>
	<script src="{{asset('admin_assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>

	<!-- <script>
		$(document).ready(function() {
			$('#example').DataTable();
			} );
	</script> -->
	<script>
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
			
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
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

    <script src="{{asset('admin_assets/js/ckeditor.js')}}"></script>
	
    <script>
        ClassicEditor.create( document.querySelector( '.editor' ),{
            ckfinder: {
                    uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}",
                }
            })
            .catch( error => {
            } );
    </script>

</body>

</html>