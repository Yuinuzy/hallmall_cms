@extends('layouts.master')

@section('title')
    @lang('translation.role')
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tables
        @endslot
        @slot('title')
            Role Management
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
                        data-bs-target="#modalTambah">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Role
                    </button>

                    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formTambahRole">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="role_name_tambah" class="form-label">Nama Role</label>
                                            <input type="text" class="form-control" id="role_name_tambah" name="name"
                                                placeholder="Contoh: admin">
                                            <div class="invalid-feedback" id="error-role-name"></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"><i
                                            class="fa fa-times me-1"></i>Tutup</button>
                                    <button type="submit" form="formTambahRole" class="btn btn-success"><i
                                            class="fa fa-save me-1"></i>Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="dataTable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Role</th>
                                <th>Jumlah User</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ubah Nama Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formEditRole">
                                        @csrf
                                        <input type="hidden" id="role_id" name="id">
                                        <div class="mb-3">
                                            <label for="role_name_edit" class="form-label">Nama Role</label>
                                            <input type="text" class="form-control" id="role_name_edit" name="name"
                                                placeholder="Contoh: admin">
                                            <div class="invalid-feedback" id="error-role-name"></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"><i
                                            class="fa fa-times me-1"></i>Tutup</button>
                                    <button type="submit" form="formEditRole" class="btn btn-success" id="btnSimpan"> <i
                                            class="fa fa-save me-1"></i>Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection


@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/role/json') }}",
                    type: "GET",
                    dataSrc: function(json) {
                        console.log(json);

                        return json.data;
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX ERROR:", error);
                        console.warn("STATUS:", status);
                        console.log("XHR OBJECT:", xhr);
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'users_count',
                        name: 'users_count',
                        className: 'text-center'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data) {
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

            $("#dataTable").on("click", ".btn-edit", function() {
                const id = $(this).data("id");

                $.get(`/role/${id}/edit`, function(role) {
                    $('#role_id').val(role.id);
                    $('#role_name_edit').val(role.name);
                    $('#modalEdit .modal-title').text('Edit Role');
                    $('#modalEdit').modal('show');

                    $('#formEditRole').off('submit').on('submit', function(e) {
                        e.preventDefault();

                        let form = $(this);
                        let formData = form.serialize() + '&_method=PUT';

                        let submitButton = $('#btnSimpan');
                        submitButton.prop("disabled", true).html(
                            `<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

                        $.ajax({
                            url: `/role/${id}/update`,
                            method: "POST",
                            data: formData,
                            success: function(res) {
                                submitButton.prop("disabled", false).html(
                                    `<i class="fa fa-save"></i> Simpan`);

                                if (res.status === true) {
                                    showAlert(res.message, "success",
                                        "Berhasil").then((result) => {
                                        if (result.isConfirmed) {
                                            $("#formEditRole")[0]
                                                .reset();
                                            $("#formEditRole .is-invalid")
                                                .removeClass(
                                                    'is-invalid');
                                            $("#formEditRole .invalid-feedback")
                                                .remove();
                                            $("#modalEdit").modal(
                                                "hide");
                                            $("#dataTable").DataTable()
                                                .ajax.reload(null,
                                                    false);
                                        }
                                    });
                                } else {
                                    showAlert(res.message, "error", "Gagal");
                                }
                            },
                            error: function(error) {
                                submitButton.prop("disabled", false).html(
                                    `<i class="fa fa-save"></i> Simpan`);
                                if (error.status === 422) {
                                    let errors = error.responseJSON.errors;

                                    $.each(errors, function(key, value) {
                                        let input = $(
                                            `#formEditRole [name="${key}"]`
                                        );
                                        input.addClass('is-invalid');
                                        input.next('.invalid-feedback')
                                            .remove();
                                        input.after(
                                            `<span class="invalid-feedback d-block">${value[0]}</span>`
                                        );
                                    });
                                } else {
                                    showAlert(error.responseText || error
                                        .statusText, "error", "Gagal");
                                }
                            }
                        });
                    });
                });

            });

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
                            url: `{{ url('/role/${id}/delete') }}`,
                            method: "DELETE",
                            success: function(res) {
                                if (res.status === true) {
                                    showAlert(res.message, "success", "Berhasil")
                                    table.ajax.reload()
                                } else {
                                    showAlert(res, "error", "Gagal")
                                }
                            },
                            error: function(err) {
                                console.error(err)
                                showAlert(err, "error", "Gagal")
                            }
                        })
                    }
                });
            })
        });

        $('#formTambahRole').on('submit', function(e) {
            e.preventDefault();

            $("#formTambahRole .is-invalid").removeClass('is-invalid');
            $("#formTambahRole .invalid-feedback").remove();

            let submitButton = $("#formTambahRole button[type='submit']");
            submitButton.prop("disabled", true).html(`<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

            const formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('role.store') }}",
                data: formData,
                success: function(res) {
                    submitButton.prop("disabled", false).html(`<i class="fa fa-save"></i> Simpan`);

                    if (res.status === true) {
                        showAlert(res.message, "success", "Berhasil").then((result) => {
                            if (result.isConfirmed) {
                                $("#formTambahRole")[0].reset()

                                $("#formTambahRole .is-invalid").removeClass('is-invalid');
                                $("#formTambahRole .invalid-feedback").remove();

                                $('#modalTambah').modal('hide');
                                $('#dataTable').DataTable().ajax.reload(null, false);
                            }
                        })
                    } else {
                        showAlert(res, "error", "Gagal")
                    }
                },
                error: function(xhr) {
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
