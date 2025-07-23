<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    // Menampilkan daftar semua user
    public function userIndex(Request $request)
    {

        return view('admin.users.userIndex');
    }

    // Menampilkan form tambah user
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil ditambahkan', 'data' => $user]);
        }
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }


    // Menampilkan detail user
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->ajax()) {
            return response()->json(['data' => $user]);
        }

        return view('admin.users.detail', compact('user'));
    }

    // Menampilkan form edit user
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->ajax()) {
            return response()->json(['data' => $user]);
        }

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function get_data($id)
    {
        $user = User::findOrFail($id);

        $role = $user->roles->pluck('name')->first();

        return response()->json([
            'status' => true,
            'message' => "by id berhasil diambil",
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'password' => '', // kosongkan untuk keamanan
                'role' => $role,
            ]
        ]);
    }

    // Update data user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:cms_users,email,$id",
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }
        $user->update($updateData);

        $user->syncRoles([$request->role]);

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil diperbarui', 'data' => $user]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Hapus user
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil dihapus']);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function json(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::transaction(function () {
                    return User::get();
                });
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
