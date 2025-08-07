@extends('layouts.master')

@section('title')
    Edit Kategori Merchant
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Kategori Merchant
        @endslot
        @slot('title')
            Edit Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Edit Kategori Merchant</h4>

                    <form id="editForm" enctype="multipart/form-data">
                        <div class="form-group row mb-3">
                            <label for="kategori" class="col-sm-3 col-form-label">Nama Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" name="kategori" id="kategori" class="form-control"
                                    value="{{ old('kategori', $kategori->kategori ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="waktu_pengiriman" class="col-sm-3 col-form-label">Waktu Kirim (Hari)</label>
                            <div class="col-sm-9">
                                <input type="number" name="waktu_pengiriman" id="waktu_pengiriman" class="form-control"
                                    value="{{ old('waktu_pengiriman', $kategori->waktu_pengiriman ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="waktu_respon" class="col-sm-3 col-form-label">Waktu Respon (Jam)</label>
                            <div class="col-sm-9">
                                <input type="number" name="waktu_respon" id="waktu_respon" class="form-control"
                                    value="{{ old('waktu_respon', $kategori->waktu_respon ?? '') }}">
                            </div>
                        </div>


                        <div class="form-group row mb-3">
                            <label for="img" class="col-sm-3 col-form-label">Gambar</label>
                            <div class="col-sm-9">
                                <input type="file" name="img" id="img" class="form-control">
                                <img id="previewImg"
                                    src="{{ old('img', $kategori->img ?? '') ? asset($kategori->img) : '' }}"
                                    class="mt-2 rounded" alt="preview"
                                    style="max-height: 120px; display: {{ isset($kategori->img) ? 'block' : 'none' }};">
                            </div>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-8">
                                <a href="{{ route('merchant_kategori.index') }}" class="btn btn-danger">Kembali</a>
                                <button type="submit" class="btn btn-success ms-2">
                                    <i class="fa fa-save me-1"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const path = window.location.pathname;
        const id = path.split("/")[2];
        $(document).ready(function() {

            $.ajax({
                url: `/merchant_kategori/${id}/get_data`,
                method: "GET",
                success: function(response) {
                    if (response.status === true) {
                        const data = response.data.get;

                        // Isi form sesuai ID input
                        $("#kategori").val(data.kategori);
                        $("#waktu_pengiriman").val(data.waktu_pengiriman);
                        $("#waktu_respon").val(data.waktu_respon);

                        // Preview gambar jika ada
                        if (data.img) {
                            let imgSrc = data.img.startsWith('http') ? data.img :
                                '{{ asset('') }}' + data.img;
                            $("#previewImg").attr("src", imgSrc).show();
                        }

                    } else {
                        showAlert("Gagal mendapatkan data.", "error", "Gagal");
                    }
                },
                error: function(err) {
                    showAlert("Gagal mengambil data.", "error", "Gagal");
                }
            });
        });



        // Submit form edit
        $("#editForm").submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            $.ajax({
                url: `/merchant_kategori/${id}`,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    submitBtn.prop("disabled", false).html(
                        `<i class="fa fa-save"></i> Simpan`);
                    if (res.status === true) {
                        showAlert(res.message, "success", "Berhasil").then(() => {
                            window.location.href =
                                "{{ route('merchant_kategori.index') }}";
                        });
                    } else {
                        showAlert(res.message || "Gagal memperbarui data", "error",
                            "Gagal");
                    }
                },
                error: function(err) {
                    submitBtn.prop("disabled", false).html(
                        `<i class="fa fa-save"></i> Simpan`);

                    if (err.status === 422) {
                        let errors = err.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            let input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(
                                `<span class="invalid-feedback d-block">${val[0]}</span>`
                            );
                        });
                    } else {
                        showAlert("Terjadi kesalahan saat menyimpan data", "error",
                            "Error");
                    }
                }
            });
        });
    </script>
@endsection
