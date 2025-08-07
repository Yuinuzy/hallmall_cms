@extends('layouts.master')

@section('title')
    @lang('translation.Form_Layouts')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Merchant Kategori
        @endslot
        @slot('title')
            Detail Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Detail Kategori Merchant</h4>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Kategori</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $data->kategori }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Slug</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $data->slug }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Waktu Kirim</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $data->waktu_pengiriman }} hari" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Waktu Respon</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $data->waktu_respon }} jam" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Gambar</label>
                        <div class="col-sm-9">
                            @if($data->img)
                                <img src="{{ filter_var($data->img, FILTER_VALIDATE_URL) ? $data->img : asset('storage/' . $data->img) }}" 
                                    width="120" height="120" style="object-fit:cover;border-radius:6px;">
                            @else
                                <p>Tidak ada gambar</p>
                            @endif
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <a href="{{ route('merchant_kategori.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
