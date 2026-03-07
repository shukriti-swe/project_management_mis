<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.user_list', compact('users'));
    }
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.roles', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);

        return redirect()->back()
            ->with('success','User roles updated successfully');
    }
}