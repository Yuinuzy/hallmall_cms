@extends('layouts.master')

@section('title')
    Edit Sub Kategori
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Sub Kategori
        @endslot
        @slot('title')
            Edit Sub Kategori
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Edit Sub Kategori</h4>

                    <form id="editForm" enctype="multipart/form-data">
                        <div class="form-group row mb-3">
                            <label for="nama" class="col-sm-3 col-form-label">Nama Sub Kategori</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="kategori_id" class="col-sm-3 col-form-label">Kategori</label>
                            <div class="col-sm-9">
                                <select name="kategori_id" id="kategori_id" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ (string) $item->id }}">{{ $item->kategori }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-8">
                                <a href="{{ route('sub_kategori.index') }}" class="btn btn-danger">Kembali</a>
                                <button type="submit" class="btn btn-success ms-2">
                                    <i class="fa fa-save me-1"></i> Simpan
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
        const id = path.split("/")[2]; // Ambil ID dari URL

        fetchData(id);

        function fetchData(id) {
            $.ajax({
                url: `/sub_kategori/${id}/data`,
                method: "GET",
                success: function (res) {
                    if (res.status === true) {
                        const data = res.data;

                        // Set nama sub kategori
                        $("#nama").val(data.name_kategori_sub);

                        // Tunggu sejenak untuk jaga-jaga jika select masih render
                        setTimeout(() => {
                            $("#kategori_id").val(String(data.id_kategori)).trigger("change");

                            // Debug log
                            console.log("ID kategori dari DB:", data.id_kategori);
                            console.log("Kategori terpilih sekarang:", $("#kategori_id").val());

                            // Cek apakah ada option dengan value tsb
                            const found = $("#kategori_id option").filter(function () {
                                return $(this).val() == data.id_kategori;
                            });

                            if (found.length === 0) {
                                console.warn("ID kategori tidak ditemukan di option!");
                            }

                        }, 200); // Delay aman
                    } else {
                        showAlert("Gagal mendapatkan data.", "error", "Gagal");
                    }
                },
                error: function () {
                    showAlert("Gagal mengambil data.", "error", "Gagal");
                }
            });
        }

            // Submit form
            $("#editForm").submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

                $.ajax({
                    url: `/sub_kategori/${id}`,
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
                                    "{{ route('sub_kategori.index') }}";
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
