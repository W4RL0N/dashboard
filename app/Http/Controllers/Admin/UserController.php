<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'assignedTasks')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'role'         => 'required|exists:roles,name',
            'avatar_color' => 'nullable|string|size:7',
            'bio'          => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'avatar_color' => $data['avatar_color'] ?? '#6366f1',
            'bio'          => $data['bio'] ?? null,
        ]);
        $user->assignRole($data['role']);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'password'     => 'nullable|min:8|confirmed',
            'role'         => 'required|exists:roles,name',
            'avatar_color' => 'nullable|string|size:7',
            'bio'          => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'avatar_color' => $data['avatar_color'] ?? $user->avatar_color,
            'bio'          => $data['bio'] ?? $user->bio,
        ]);
        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
