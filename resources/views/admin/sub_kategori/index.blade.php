@extends('layouts.master')

@section('title')
    Sub Kategori Merchant
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tables
        @endslot
        @slot('title')
            Sub Kategori Merchant
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List Sub Kategori Merchant</h4>
                    <a href="{{ route('sub_kategori.create') }}" class="btn btn-primary mb-2">
                        <i class="fa fa-plus me-1"></i>Tambah Sub Kategori
                    </a>

                    <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kategori</th>
                                <th>Nama Sub Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/sub_kategori/json') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'merchant_kategori.kategori', // relasi dari kategori
                    name: 'merchantKategori.kategori'
                },
                {
                    data: 'name_kategori_sub',
                    name: 'name_kategori_sub'
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data) {
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

        $("#dataTable").on("click", ".btn-delete", function() {
            const id = $(this).data("id");

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
                        url: `/merchant_kategori_sub/${id}/delete`,
                        method: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.status === true) {
                                showAlert(res.message, "success", "Berhasil");
                                $("#dataTable").DataTable().ajax.reload();
                            } else {
                                showAlert(res.message || "Gagal menghapus data.", "error", "Gagal");
                            }
                        },
                        error: function(err) {
                            console.error(err);
                            const msg = err.responseJSON?.message ?? "Terjadi kesalahan saat menghapus.";
                            showAlert(msg, "error", "Gagal");
                        }
                    });
                }
            });
        });

        $("#dataTable").on("click", ".btn-edit", function() {
            const id = $(this).data("id");
            window.location.href = `sub_kategori/${id}/edit`;
        });

        $("#dataTable").on("click", ".btn-details", function() {
            const id = $(this).data("id");
            window.location.href = `/sub_kategori/${id}/detail`;
        });
    </script>
@endsection
