@extends('layouts.master')

@section('title')
    @lang('translation.userIndex')
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
            User Management
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">List User</h4>
                    <a href="{{ route('users.create') }}" class="btn btn-primary mb-2"><i class="fa fa-plus me-1"></i>Tambah
                        User</a>

                    <table id="dataTable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
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
                    url: "{{ url('/users/json') }}",
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
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return `
                                <button class="btn btn-sm btn-info btn-details" data-id="${data}">
                                    <i class="fa fa-address-card"></i> Detail
                                </button>
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

            $("#dataTable").on("click", ".btn-details", function() {
                const id = $(this).data("id");
                window.location.href = `/users/${id}`;
            });

            $("#dataTable").on("click", ".btn-edit", function() {
                const id = $(this).data("id");

                console.log(id);

                window.location.href = `{{ url('/users/${id}/edit') }}`
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
                            url: `{{ url('/users/${id}/delete') }}`,
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
    </script>
@endsection
