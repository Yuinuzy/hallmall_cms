@extends('layouts.master')

@section('title')
    Detail Sub Kategori
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Sub Kategori
        @endslot
        @slot('title')
            Detail Sub Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Detail Sub Kategori</h4>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Sub Kategori</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $data->name_kategori_sub }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Kategori Utama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" 
                                   value="{{ $data->merchantKategori->kategori ?? 'Tidak Diketahui' }}" readonly>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <a href="{{ route('sub_kategori.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
