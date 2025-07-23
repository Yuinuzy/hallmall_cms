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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role berhasil ditambahkan.']);
    }
    // Menampilkan form edit role (optional)
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    // Mengupdate role user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        return response()->json(['message' => 'Role berhasil diupdate']);
    }
}
