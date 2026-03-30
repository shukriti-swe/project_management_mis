<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{asset('admin_assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon">
		</div>
		<div>
			<h4 class="logo-text">PranRFL MIS </h4>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
		</div>
	</div>

	<!--navigation-->
	<ul class="metismenu" id="menu">
		<li>
			<a href="{{ route('dashboard') }}" class="has-arrow">
				<div class="parent-icon"><i class='bx bx-home-circle'></i>
				</div>
				<div class="menu-title">Dashboard</div>
			</a>
		</li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Users</div>
			</a>
			<ul>
				<li><a href="{{ route('users.index') }}"><i class="bx bx-right-arrow-alt"></i>User List</a></li>
				<li><a href="{{ route('users.create') }}"><i class="bx bx-right-arrow-alt"></i>Add User</a></li>
			</ul>
		</li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Roles & Premission</div>
			</a>
			<ul>
				<li> <a href="{{ route('roles.index') }}"><i class="bx bx-right-arrow-alt"></i>Roles</a></li>
				<li> <a href="{{ route('permissions.index') }}"><i class="bx bx-right-arrow-alt"></i>Permission</a></li>
			</ul>
		</li>

		<li class="menu-label">More Pages</li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-user-circle"></i></div>
                <div class="menu-title">Statuses</div>
            </a>
            <ul>
                <li><a href="{{ route('status.index') }}"><i class="bx bx-right-arrow-alt"></i>Status List</a></li>
                <li><a href="{{ route('status.create') }}"><i class="bx bx-right-arrow-alt"></i>Add Status</a></li>
            </ul>
        </li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Project & Layers</div>
			</a>
			<ul>
				<li><a href="{{ route('projectWithLayers') }}"><i class="bx bx-right-arrow-alt"></i>Project with layers</a></li>
			</ul>
		</li>

		<li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-user-circle"></i></div>
                <div class="menu-title">Layer Types</div>
            </a>
            <ul>
                <li><a href="{{ route('layerType.index') }}"><i class="bx bx-right-arrow-alt"></i>Layer Type List</a></li>
                <li><a href="{{ route('layerType.create') }}"><i class="bx bx-right-arrow-alt"></i>Add Type</a></li>
            </ul>
        </li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Project Individual</div>
			</a>
			<ul>
				<li><a href="{{ route('projectList') }}"><i class="bx bx-right-arrow-alt"></i>Project List</a></li>
				<li><a href="{{ route('addProject') }}"><i class="bx bx-right-arrow-alt"></i>Add Project</a></li>
			</ul>
		</li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Layers</div>
			</a>
			<ul>
				<li><a href="{{ route('layerList') }}"><i class="bx bx-right-arrow-alt"></i>Layer List</a></li>
				<li><a href="{{ route('layer.create') }}"><i class="bx bx-right-arrow-alt"></i>Add Layer</a></li>
			</ul>
		</li>

		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Reports</div>
			</a>
			<ul>
				<li><a href="{{ route('projectSammary') }}"><i class="bx bx-right-arrow-alt"></i>Project Sammary</a></li>
				<li><a href="#"><i class="bx bx-right-arrow-alt"></i>User List</a></li>
			</ul>
		</li>

		<li>
			<a href="{{ route('logout') }}">
				<div class="parent-icon"><i class="bx bx-user-circle"></i></div>
				<div class="menu-title">Logout</div>
			</a>
		</li>

	</ul>
</div>

<!--end sidebar wrapper -->
<!--start header -->