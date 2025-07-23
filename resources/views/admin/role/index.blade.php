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
                        data-bs-target="#exampleModal">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Role
                    </button>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                                            <label for="role_name" class="form-label">Nama Role</label>
                                            <input type="text" class="form-control" id="role_name" name="name"
                                                placeholder="Contoh: admin">
                                            <div class="invalid-feedback" id="error-role-name"></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" form="formTambahRole" class="btn btn-primary">Simpan</button>
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

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Ubah Nama Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="formRole">
                                        @csrf
                                        <input type="hidden" id="role_id" name="id">
                                        <div class="mb-3">
                                            <label for="role_name" class="form-label">Nama Role</label>
                                            <input type="text" class="form-control" id="role_name" name="name"
                                                placeholder="Contoh: admin">
                                            <div class="invalid-feedback" id="error-role-name"></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" form="formRole" class="btn btn-primary"
                                        id="btnSimpan">Simpan</button>
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
                        console.log("DATA DARI SERVER (console.log):", json
                            .data); // :white_check_mark: tampilkan data di console
                        return json.data; // tetap kembalikan data untuk DataTables
                    },
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
                    $('#role_name').val(role.name);
                    $('#exampleModal .modal-title').text('Edit Role');
                    $('#exampleModal').modal('show');

                    // Ubah form ID untuk edit
                    $('#formTambahRole').attr('id', 'formEditRole');
                    $('#formEditRole').off('submit').on('submit', function(e) {
                        e.preventDefault();
                        const formData = $(this).serialize();

                        $.ajax({
                            url: `/role/${id}/update`,
                            method: 'PUT',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function() {
                                $('#exampleModal').modal('hide');
                                $('#dataTable').DataTable().ajax.reload();
                                $('#formEditRole')[0].reset();
                            },
                            error: function(xhr) {
                                const errors = xhr.responseJSON.errors;
                                if (errors?.name) {
                                    $('#role_name').addClass('is-invalid');
                                    $('#error-role-name').text(errors.name[0]);
                                }
                            }
                        });
                    });
                });
            });

            $("#dataTable").on("click", ".btn-delete", function() {
                const id = $(this).data("id");
                const confirmed = confirm("Yakin ingin menghapus user ini?");
                if (!confirmed) return;

                $.ajax({
                    url: `/users/${id}/delete`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert("User berhasil dihapus!");
                        $('#dataTable').DataTable().ajax.reload(); // refresh tabel
                    },
                    error: function(xhr) {
                        alert("Gagal menghapus user: " + (xhr.responseJSON?.message ||
                            'Terjadi kesalahan'));
                    }
                });
            });
        });

        $('#formTambahRole').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('role.store') }}",
                data: formData,
                success: function(res) {
                    $('#exampleModal').modal('hide');
                    $('#dataTable').DataTable().ajax.reload();
                    $('#formTambahRole')[0].reset();
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors?.name) {
                        $('#role_name').addClass('is-invalid');
                        $('#error-role-name').text(errors.name[0]);
                    }
                }
            });
        });
    </script>
@endsection
