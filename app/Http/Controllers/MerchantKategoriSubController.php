<?php

namespace App\Http\Controllers;

use App\Models\MerchantKategori;
use App\Models\SubKategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MerchantKategoriSubController extends Controller
{
    public function index()
    {
        return view('admin.sub_kategori.index');
    }

    public function json(Request $request)
    {
        $data = SubKategori::with('merchantKategori');

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function destroy($id)
    {
        $data = SubKategori::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    public function create()
    {
        $merchantKategoriList = \App\Models\MerchantKategori::all(); // Ambil semua kategori
        return view('admin.sub_kategori.create', compact('merchantKategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merchant_kategori_id' => 'required|exists:merchantkategori,id',
            'name_kategori_sub' => 'required|string|max:255',
        ]);

        $subKategori = new SubKategori();
        $subKategori->id_kategori = $request->merchant_kategori_id;
        $subKategori->name_kategori_sub = $request->name_kategori_sub;
        $subKategori->create_date = now(); // isi tanggal dan jam saat ini
        $subKategori->create_by = auth()->id(); // isi dengan ID user yang login
        $subKategori->save();

        return response()->json([
            'status' => true,
            'message' => 'Sub Kategori berhasil ditambahkan!'
        ]);
    }



    public function edit($id)
    {
        $kategori = MerchantKategori::all(); // Kalau ada select
        return view('admin.sub_kategori.edit', compact('kategori'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'kategori_id' => 'required|exists:merchantkategori,id',
        ]);

        $sub = SubKategori::findOrFail($id);
        $sub->update([
            'name_kategori_sub' => $request->nama,
            'id_kategori' => $request->kategori_id,
            'updated_by' => auth()->id(),
            'modify_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sub Kategori berhasil diperbarui.',
        ]);
    }

    public function getData($id)
    {
        $data = SubKategori::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }


    public function detail($id)
    {
        // nanti isi view untuk detail
        $data = SubKategori::with('merchantKategori')->findOrFail($id);
        return view('admin.sub_kategori.detail', compact('data'));
    }
}
