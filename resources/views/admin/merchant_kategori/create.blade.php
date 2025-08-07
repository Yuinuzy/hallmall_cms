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
            Form Tambah Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Tambah Kategori</h4>

                    <form id="createKategoriForm" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <label for="kategori" class="col-sm-3 col-form-label">Nama Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kategori"
                                    placeholder="Masukan Nama Kategori">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="waktu_pengiriman" class="col-sm-3 col-form-label">Waktu Kirim (Hari)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="waktu_pengiriman" placeholder="Contoh: 2">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="waktu_respon" class="col-sm-3 col-form-label">Waktu Respon (Jam)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="waktu_respon" placeholder="Contoh: 60">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="img" class="col-sm-3 col-form-label">Gambar</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="img" accept="image/*"
                                    onchange="previewImage(event)">
                                <img id="img-preview" src="#" alt="Preview Gambar" class="mt-3 d-none"
                                    style="max-height: 150px;">
                            </div>
                        </div>



                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <div>
                                    <a href="{{ route('merchant_kategori.index') }}" class="btn btn-danger">Kembali</a>
                                    <button type="submit" class="btn btn-success ms-2">
                                        <i class="fa fa-save me-1"></i>Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            let output = document.getElementById('img-preview');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.classList.remove("d-none");
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            }
        }

        $("#createKategoriForm").submit(function(e) {
            e.preventDefault();

            $("#createKategoriForm .is-invalid").removeClass('is-invalid');
            $("#createKategoriForm .invalid-feedback").remove();

            let submitButton = $("#createKategoriForm button[type='submit']");
            submitButton.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('merchant_kategori.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save"></i> Simpan`);

                    if (res.status === true) {
                        showAlert(res.message || 'Kategori berhasil ditambahkan!', "success",
                                "Berhasil")
                            .then((result) => {
                                if (result.isConfirmed) {
                                    $("#createKategoriForm")[0].reset();
                                    window.location.href = "{{ route('merchant_kategori.index') }}";
                                }
                            });
                    } else {
                        showAlert(res.message || res, "error", "Gagal");
                    }
                },
                error: function(error) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save"></i> Simpan`);

                    if (error.status === 422) {
                        let errors = error.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            let input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
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
