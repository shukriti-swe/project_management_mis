<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link href="{{asset('admin_assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
	<link href="{{asset('admin_assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/fancy-file-uploader/fancy_fileupload.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />

	<!-- loader-->
	<link href="{{asset('admin_assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{asset('assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('admin_assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{asset('admin_assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('admin_assets/css/icons.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->
	
	<link rel="stylesheet" href="{{asset('admin_assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{asset('admin_assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{asset('admin_assets/css/header-colors.css')}}" />
	<link href="{{asset('admin_assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <title>Pran RFL Group</title>

   
</head>

<body class="antialiased fullscreen-bg">
    

    <div class="container">

        @yield('content')
        
    </div>
   

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

	<script>
		$(document).ready(function() {
			$('#example').DataTable();
			} );
	</script>
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
</body>

</html>