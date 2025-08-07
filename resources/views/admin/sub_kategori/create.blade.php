@extends('layouts.master')

@section('title')
    Tambah Sub Kategori
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
            Sub Kategori
        @endslot
        @slot('title')
            Form Tambah Sub Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Tambah Sub Kategori</h4>

                    <form id="createSubKategoriForm">
                        <div class="row mb-4">
                            <label for="merchant_kategori_id" class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <select name="merchant_kategori_id" class="form-control">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($merchantKategoriList as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="nama_sub_kategori" class="col-sm-3 col-form-label">Nama Sub Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name_kategori_sub"
                                    placeholder="Masukkan Nama Sub Kategori">
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <div>
                                    <a href="{{ route('sub_kategori.index') }}" class="btn btn-danger">Kembali</a>
                                    <button type="submit" class="btn btn-success ms-2">
                                        <i class="fa fa-save me-1"></i>Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('script')
    <script>
        $("#createSubKategoriForm").submit(function(e) {
            e.preventDefault();

            $(".is-invalid").removeClass("is-invalid");
            $(".invalid-feedback").remove();

            let submitButton = $("#createSubKategoriForm button[type='submit']");
            submitButton.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            $.ajax({
                url: "{{ route('sub_kategori.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save me-1"></i> Simpan`);

                    if (res.status === true) {
                        showAlert(res.message || 'Sub Kategori berhasil ditambahkan!', "success",
                                "Berhasil")
                            .then(() => {
                                window.location.href = "{{ route('sub_kategori.index') }}";
                            });
                    } else {
                        showAlert(res.message || res, "error", "Gagal");
                    }
                },
                error: function(error) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save me-1"></i> Simpan`);

                    if (error.status === 422) {
                        let errors = error.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            let input = $(`[name="${key}"]`);
                            input.addClass("is-invalid");
                            input.after(
                                `<span class="invalid-feedback d-block">${value[0]}</span>`);
                        });
                    } else {
                        showAlert(error.responseText || error.statusText, "error", "Gagal");
                    }
                }
            });
        });
    </script>
@endsection
