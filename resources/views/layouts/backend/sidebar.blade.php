<style>
    .logout-form {
        margin: 0;
        padding: 2px 15px;
    }

    .logout-menu-btn {
        color: #5f5f5f;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
    }
    .logout-menu-btn:focus {
        outline: none;
    }

    .logout-form:hover {
        background-color: #f1f5f9;
    }

    .logout-icon {
        font-size: 24px;
        display: flex;
        align-items: center;
    }

    .logout-text {
        font-size: 15px;
    }
</style>
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
            <a href="{{ route('board') }}">
                <div class="parent-icon"><i class="bx bx-user-circle"></i></div>
                <div class="menu-title">Board</div>
            </a>
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

{{--        <li>--}}
{{--            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="padding: 2px 15px">--}}
{{--                @csrf--}}
{{--                <button type="submit" class="menu-link-btn" style="display: flex; align-items: center;cursor: pointer; gap: 10px;">--}}
{{--                    <div class="parent-icon" style="font-size: 24px"><i class="bx bx-user-circle"></i></div>--}}
{{--                    <div class="menu-title" style="font-size: 15px">Logout</div>--}}
{{--                </button>--}}
{{--            </form>--}}
{{--        </li>--}}
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-menu-btn">
                    <div class="logout-icon">
                        <i class="bx bx-user-circle"></i>
                    </div>
                    <div class="logout-text">Logout</div>
                </button>
            </form>
        </li>

{{--        <a href="{{ route('logout') }}">--}}
{{--            <div class="parent-icon"><i class="bx bx-user-circle"></i></div>--}}
{{--            <div class="menu-title">Logout</div>--}}
{{--        </a>--}}

	</ul>
</div>

<!--end sidebar wrapper -->
<!--start header -->