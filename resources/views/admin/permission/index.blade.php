@extends('layouts.master')

@section('title', 'Banner')

@section('css')
@include("components.css.style-datatable")
@endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboard @endslot
@slot('title') Permission @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                    <i class="fa fa-plus"></i> Tambah Data
                </button>

                <hr>

                <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Nama</th>
                            <th>Akses Keamanan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    <i class="fa fa-plus"></i> Tambah Data
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="formTambah">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"> Permission Name </label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Masukkan Permission Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    <i class="fa fa-edit"></i> Edit Data
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="formEdit">
                <input type="hidden" id="idEdit">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"> Permission Name </label>
                        <input type="text" class="form-control" name="name" id="nameEdit"
                            placeholder="Masukkan Permission Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Edit -->

@endsection

@section('script')

@include("components.javascript.datatable")

<script type="text/javascript">
    $(document).ready(function () {
        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/permission/json') }}",
                type: "GET",
                dataSrc: function (json) {
                    console.log(json);
                    
                    return json.data;
                },
                error: function (xhr, status, error) {
                    console.error("AJAX ERROR:", error);
                    console.warn("STATUS:", status);
                    console.log("XHR OBJECT:", xhr);
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'name', name: 'name' },
                { data: 'guard_name', name: 'guard_name' },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type, row, meta) {
                        return `
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${data}">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${data}">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        `;
                    }
                }
            ]
        });

        $("#formTambah").submit(function(e) {
            e.preventDefault()

            $("#formTambah .is-invalid").removeClass('is-invalid');
            $("#formTambah .invalid-feedback").remove();

            let submitButton = $("#formTambah button[type='submit']");
            submitButton.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            $.ajax({
                url: "{{ url('/permission/store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save"></i> Simpan`);

                    if (res.status === true) {
                        showAlert(res.message, "success", "Berhasil").then((result) => {
                            if (result.isConfirmed) {
                                $("#formTambah")[0].reset()

                                $("#formTambah .is-invalid").removeClass('is-invalid');
                                $("#formTambah .invalid-feedback").remove();

                                $('#modalTambah').modal('hide');
                                $('#dataTable').DataTable().ajax.reload(null, false);
                            }
                        })
                    } else {
                        showAlert(res, "error", "Gagal")
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

                            input.after(`<span class="invalid-feedback d-block">${value[0]}</span>`);
                        });
                    } else {
                        showAlert(error.responseText || error.statusText, "error", "Gagal");
                    }
                }
            })
        })

        $("#dataTable").on("click", ".btn-edit", function() {
            const id = $(this).data("id")

            $.ajax({
                url: `{{ url('/permission/${id}/get-data') }}`,
                method: "GET",
                success: function(response) {
                    if (response.status === true) {
                        $("#idEdit").val(response.data.get.id)
                        $("#nameEdit").val(response.data.get.name);
                        $("#modalEdit").modal("show")
                    } else {
                        showAlert(response.message, "error", "Gagal")
                    }
                },
                error: function(error) {
                    showAlert(error.message, "error", "Gagal")
                }
            })
        })

        $("#dataTable").on("click", ".btn-delete", function() {
            const id = $(this).data("id")

            Swal.fire({
                title: "Apakah Ingin Menghapus Data Ini?",
                text: "Klik Ya Untuk Menghapus Data",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus Data",
                cancelButtonText: "Kembali",
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/permission/${id}/delete') }}`,
                        method: "DELETE",
                        success: function (res) {
                            if (res.status === true) {
                                showAlert(res.message, "success", "Berhasil")
                                table.ajax.reload()
                            } else {
                                showAlert(res, "error", "Gagal")
                            }
                        },
                        error: function (err) {
                            console.error(err)
                            showAlert(err, "error", "Gagal")
                        }
                    })
                }
            });
        })

        $("#formEdit").submit(function(e) {
            e.preventDefault();

            $("#formEdit .is-invalid").removeClass('is-invalid');
            $("#formEdit .invalid-feedback").remove();

            let submitButton = $("#formEdit button[type='submit']");
            submitButton.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            const id = $("#idEdit").val();

            let formSerialized = $(this).serialize() + '&_method=PUT';

            $.ajax({
                url: `{{ url('/permission/${id}') }}`,
                method: "POST",
                data: formSerialized,
                success: function (res) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save"></i> Simpan`);

                    if (res.status === true) {
                        showAlert(res.message, "success", "Berhasil").then((result) => {
                            if (result.isConfirmed) {
                                $("#formEdit")[0].reset();
                                $("#formEdit .is-invalid").removeClass('is-invalid');
                                $("#formEdit .invalid-feedback").remove();
                                $("#modalEdit").modal("hide");
                                $("#dataTable").DataTable().ajax.reload(null, false);
                            }
                        });
                    } else {
                        showAlert(res.message, "error", "Gagal");
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

                            input.after(`<span class="invalid-feedback d-block">${value[0]}</span>`);
                        });
                    } else {
                        showAlert(error.responseText || error.statusText, "error", "Gagal");
                    }
                }
            });
        });

    });
</script>

@endsection
