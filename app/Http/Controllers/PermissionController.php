<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    protected $permission;

    public function __construct()
    {
        $this->permission = new Permission();
    }

    public function index()
    {
        return view("admin.permission.index");
    }

    public function create()
    {
        return view("admin.permission.create");
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:3|unique:permissions,name',
            ];

            $messages = [
                'name.required' => 'Nama Kategori Wajib Diisi'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $this->permission->create([
                "name" => $request["name"],
                "guard_name" => "web"
            ]);

            DB::commit();

            return ResponseHelper::successResponse("Create Data Successfully", "201");

        } catch (\Exception $e) {
            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function get_data($id)
    {
        try {

            DB::beginTransaction();

            $data["get"] = $this->permission->where("id", $id)->first();

            DB::commit();

            return ResponseHelper::getDataSuccessResponse("Get Data By ID Successfully", $data, 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $rules = [
                'name' => 'required|min:3|unique:permissions,name',
            ];

            $messages = [
                'name.required' => 'Nama Kategori Wajib Diisi',
                'name.unique' => 'Nama Kategori Sudah Ada',
                'name.min' => 'Nama Kategori Minimal 3 Karakter'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $this->permission->where("id", $id)->update([
                "name" => $request["name"]
            ]);

            DB::commit();

            return ResponseHelper::successResponse("Update Data Successfully", 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $this->permission->where("id", $id)->delete();

            DB::commit();

            return ResponseHelper::successResponse("Delete Data By ID Successfully", 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function change_status($id)
    {
        try {

            DB::beginTransaction();

            $cek = $this->permission->where("id", $id)->first();

            if ($cek["status"] == "1") {
                $cek->update([
                    "status" => "0"
                ]);
            } else if ($cek["status"] == "0") {
                $cek->update([
                    "status" => "1"
                ]);
            }

            DB::commit();

            return ResponseHelper::successResponse("Change Status Successfully", 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e->getMessage(), 500);
        }
    }

    public function json(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::transaction(function () {
                    return $this->permission->orderBy("id", "DESC")->get();
                });

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        } catch (\Exception $e) {

            DB::rollBack();

            return ResponseHelper::errorResponse($e, 500);
        }
    }
}
