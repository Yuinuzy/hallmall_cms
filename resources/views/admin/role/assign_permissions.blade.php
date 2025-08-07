@extends('layouts.master')

@section('title')
    Tambah Akses - {{ $role->name }}
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Role
        @endslot
        @slot('title')
            Tambah Akses untuk Role: {{ $role->name }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <a href="{{ route('roles.add.permissions', $role->id) }}" class="btn btn-primary mb-3">
                        <i class="fa fa-plus"></i> Tambah Akses
                    </a>

                    <form id="formAssignPermission" method="POST"
                        action="{{ route('roles.assign.permissions.save', $role->id) }}">
                        @csrf

                        <table id="permissionsTable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Permission</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        <a href="{{ route('role.index') }}" class="btn btn-danger">Kembali</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables scripts -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const roleId = {{ $role->id }};
            const table = $('#permissionsTable').DataTable({
                processing: true,
                serverSide: false, // karena semua permission di-load sekaligus
                ajax: {
                    url: `/role/${roleId}/permissions/json`, // pastikan route ini ada
                    method: 'GET',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: null,
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button type="button" class="btn btn-sm btn-danger btn-remove-permission"
                                    data-permission="${row.name}">
                                <i class="fa fa-trash"></i> Hapus </button>
                            `;
                        }
                    }
                ]
            });
            $('#permissionsTable').on('click', '.btn-remove-permission', function() {
                const permissionName = $(this).data('permission');

                if (confirm(`Yakin ingin menghapus akses "${permissionName}" dari role ini?`)) {
                    $.ajax({
                        url: `/role/${roleId}/revoke-permission`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            permission_name: permissionName
                        },
                        success: function(res) {
                            alert(res.message);
                            $('#permissionsTable').DataTable().ajax.reload();
                        },
                        error: function(err) {
                            alert('Gagal mencabut permission.');
                            console.error(err);
                        }
                    });
                }
            });

        });
    </script>
@endsection
