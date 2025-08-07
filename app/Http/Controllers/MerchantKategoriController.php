<?php

namespace App\Http\Controllers;

use App\Models\MerchantKategori;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;



class MerchantKategoriController extends Controller
{
    public function index()
    {
        $kategori = MerchantKategori::orderBy('order_by')->get();
        return view('admin.merchant_kategori.index', compact('kategori'));
    }

    public function json()
    {
        $data = MerchantKategori::select(['id', 'kategori', 'slug', 'img', 'waktu_pengiriman', 'waktu_respon']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('img', function ($row) {
                if ($row->img) {
                    // Jika sudah berupa URL (http/https), langsung pakai
                    if (filter_var($row->img, FILTER_VALIDATE_URL)) {
                        return $row->img;
                    }

                    // Kalau path lokal, tambahkan storage/
                    return asset('storage/' . $row->img);
                }
                return null;
            })

            ->make(true);
    }


    public function create()
    {
        return view('admin.merchant_kategori.create');
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'kategori' => 'required|min:3|unique:merchantkategori,kategori',
                'waktu_pengiriman' => 'required|integer|min:1',
                'waktu_respon' => 'required|integer|min:1',
                'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ];

            $messages = [
                'kategori.required' => 'Nama Kategori wajib diisi',
                'img.image' => 'File harus berupa gambar',
                'img.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
                'img.max' => 'Ukuran gambar maksimal 2MB',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $data = [
                'kategori' => $request->kategori,
                'slug' => Str::slug($request->kategori),
                'waktu_pengiriman' => $request->waktu_pengiriman,
                'waktu_respon' => $request->waktu_respon,
            ];

            if ($request->hasFile('img')) {
                $path = $request->file('img')->store('uploads/merchantkategori', 'public');
                $data['img'] = $path;
            }

            MerchantKategori::create($data);

            DB::commit();

            return ResponseHelper::successResponse("Kategori berhasil ditambahkan", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
    }

    public function edit($id)
    {
        return view('admin.merchant_kategori.edit', compact('id'));
    }

    public function getData($id)
    {
        try {
            DB::beginTransaction();

            $data["get"] = MerchantKategori::where("id", $id)->first();

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
                'kategori' => 'required|min:3|unique:merchantkategori,kategori,' . $id,
                'waktu_pengiriman' => 'required|integer|min:1',
                'waktu_respon' => 'required|integer|min:1',
                'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ];

            $messages = [
                'kategori.required' => 'Nama Kategori wajib diisi',
                'img.image' => 'File harus berupa gambar',
                'img.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
                'img.max' => 'Ukuran gambar maksimal 2MB',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return ResponseHelper::validationErrorResponse($validator);
            }

            DB::beginTransaction();

            $kategori = MerchantKategori::findOrFail($id);

            $data = [
                'kategori' => $request->kategori,
                'slug' => Str::slug($request->kategori),
                'waktu_pengiriman' => $request->waktu_pengiriman, // dalam hari
                'waktu_respon' => $request->waktu_respon,         // dalam jam
            ];

            if ($request->hasFile('img')) {
                $filename = time() . '.' . $request->file('img')->getClientOriginalExtension();
                $request->file('img')->move(public_path('uploads/merchantkategori'), $filename);
                $data['img'] = 'uploads/merchantkategori/' . $filename;
            }

            $kategori->update($data);

            DB::commit();

            return ResponseHelper::successResponse("Kategori berhasil diperbarui", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
    }


    public function show($id)
    {
        $data = MerchantKategori::findOrFail($id);

        return view('admin.merchant_kategori.detail', compact('data'));
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $kategori = MerchantKategori::findOrFail($id);

            // Jika nanti kamu ingin cek relasi (misal: $kategori->merchants()->count()), bisa ditambahkan di sini

            $kategori->delete();

            DB::commit();
            return ResponseHelper::successResponse('Kategori berhasil dihapus.', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::errorResponse($e, 500);
        }
    }
}
