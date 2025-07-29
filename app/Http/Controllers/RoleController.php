<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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

            $role->syncPermissions($request->permissions ?? []);

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

    public function editInline($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.role._edit_inline', compact('role', 'permissions'))->render();
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        $jumlahUser = $role->users()->count();

        return view('admin.role.show', compact('role', 'permissions', 'jumlahUser'));
    }

    public function permissionsJson($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions()->get(); // hanya ambil permission yang sudah dimiliki role

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('jumlah_role', function ($permission) {
                return $permission->roles()->count();
            })
            ->addColumn('aksi', function ($permission) {
                return '<button class="btn btn-sm btn-primary">Edit</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }




    public function syncPermissions(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->syncPermissions($request->permissions ?? []);

            return redirect()->back()->with('success', 'Permission berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan permission.');
        }
    }

    public function assignPermissionsPage($id)
    {


        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.role.assign_permissions', compact('role', 'permissions'));
    }

    public function addPermissionsPage($id)
    {

        $role = Role::with("permissions")->findOrFail($id);

        // dd($role->hasPermission);
        $permissions = Permission::all();


        return view('admin.role.add_permissions', compact('role', 'permissions'));
    }

    public function assignPermissionsSave(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $permissionIds = $request->input('permissions', []);

        // Ambil nama permission dari ID
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        // Sinkronisasi permission ke role
        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.assign.permissions', $id)
            ->with('success', 'Permissions assigned successfully.');
    }


    public function addPermissionsSave(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $permissionIds = $request->input('permissions', []);

        // KONVERSI ID KE NAMA
        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.assign.permissions', ['id' => $id])->with('success', 'Permission berhasil ditambahkan');
    }
}
