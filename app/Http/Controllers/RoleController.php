<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

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

        return response()->json([
            'status' => true,
            'message' => 'Role berhasil ditambahkan'
        ]);
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
        try {

            $rules = [
                'name' => 'required|unique:roles,name,' . $id,
            ];

            $messages = [
                'name.required' => 'Nama Role Wajib Diisi',
                'name.unique' => 'Nama Role Sudah Ada',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Role berhasil diupdate'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function destroy($id)
    {
         try {
        DB::beginTransaction();

        $role = Role::findOrFail($id);

        // Cek jika role sedang digunakan oleh user
        if ($role->users()->count() > 0) {
            DB::rollBack();
            return ResponseHelper::errorResponse('Role sedang digunakan oleh user dan tidak dapat dihapus.', 400);
        }

        $role->delete();

        DB::commit();
        return ResponseHelper::successResponse('Role berhasil dihapus.', 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return ResponseHelper::errorResponse($e, 500);
    }
    }
}
