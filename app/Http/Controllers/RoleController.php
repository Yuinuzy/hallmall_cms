<?php

namespace App\Http\Controllers;

use App\Models\CMSUser;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    // Menampilkan halaman role management
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.role.index', compact('roles'));
    }

    // Endpoint JSON untuk DataTables user-role
    public function json()
    {
        $roles = Role::withCount('users')->get();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->make(true);
    }
    // Menampilkan form edit role (optional)
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $roles = Role::all(); // Untuk dropdown jika perlu

        return view('admin.role.edit', compact('role', 'roles'));
    }

    // Mengupdate role user
    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = CMSUser::findOrFail($id);
        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Role berhasil diperbarui']);
    }
}
