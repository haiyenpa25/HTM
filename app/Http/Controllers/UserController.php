<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'roles' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Gán vai trò cho người dùng
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->roles) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('users.index')->with('success', 'Người dùng đã được cập nhật.');
    }

    public function destroy(User $user)
    {
        if ($user->email === 'admin') {
            return redirect()->route('users.index')->with('error', 'Không thể xóa user mặc định (admin).');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Người dùng đã được xóa.');
    }

    public function permissions()
    {
        Log::info('Truy cập phương thức permissions trong UserController');
        $users = User::with('roles', 'permissions')->get();
        Log::info('Số lượng người dùng: ' . $users->count());
        $roles = Role::all();
        Log::info('Số lượng vai trò: ' . $roles->count());
        $permissions = Permission::all();
        Log::info('Số lượng quyền: ' . $permissions->count());
        return view('users.permissions', compact('users', 'roles', 'permissions'));
    }

    public function updatePermissions(Request $request)
    {
        $users = User::all();
        foreach ($users as $user) {
            $userRoles = $request->input("roles.{$user->id}", []);
            $userPermissions = $request->input("permissions.{$user->id}", []);
            $user->syncRoles($userRoles);
            $user->syncPermissions($userPermissions);
        }

        return redirect()->route('users.permissions')->with('success', 'Phân quyền đã được cập nhật.');
    }
}
