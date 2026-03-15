<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Project Management') }}</title>
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	
	<!--favicon-->
	<!-- <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" /> -->

	<script src="{{asset('admin_assets/js/jquery.min.js')}}"></script>
	<!--plugins-->
	<link href="{{asset('admin_assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
	<link href="{{asset('admin_assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/fancy-file-uploader/fancy_fileupload.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />

	<!-- loader-->
	<link href="{{asset('admin_assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{asset('admin_assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('admin_assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{asset('admin_assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('admin_assets/css/icons.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->

    <link href="{{asset('admin_assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
	<link href="{{asset('admin_assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet" />
	
	<link rel="stylesheet" href="{{asset('admin_assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{asset('admin_assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{asset('admin_assets/css/header-colors.css')}}" />
	<link href="{{asset('admin_assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />


	<script src="{{asset('js/ckeditor.js')}}"></script>

</head>

<body>
<!--wrapper-->
<div class="wrapper">