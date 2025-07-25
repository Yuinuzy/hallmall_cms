<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHelper;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


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
        try {
            $rules = [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'role' => 'required|exists:roles,name',
            ];

            $messages = [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'role.required' => 'Role wajib dipilih.',
                'role.exists' => 'Role tidak valid.'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('password'), // default password
            ]);

            $user->assignRole($request->role);

            DB::commit();

            return ResponseHelper::successResponse('User berhasil ditambahkan', 201, $user);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
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
        try {
            $rules = [
                'name' => 'required',
                'email' => "required|email|unique:users,email,$id",
                'role' => 'required|exists:roles,name',
            ];

            $messages = [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'role.required' => 'Role wajib dipilih.',
                'role.exists' => 'Role tidak valid.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $user->syncRoles([$request->role]);

            DB::commit();

            return ResponseHelper::successResponse('User berhasil diperbarui', 200, $user);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
    }


    // Hapus user
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            return ResponseHelper::successResponse('User berhasil dihapus', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
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
