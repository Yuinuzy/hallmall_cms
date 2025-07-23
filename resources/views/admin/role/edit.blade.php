@extends('layouts.master')

@section('title') @lang('translation.Form_Layouts') @endsection

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
@slot('li_1') User Management @endslot
@slot('title') Form Edit User @endslot
@endcomponent

<div class="row">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit User</h4>

                <form id="createUserForm">
                    <div class="row mb-4">
                        <label for="name" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="role" class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="role" id="role-select">
                                <option disabled selected>Pilih Role</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->id }}" {{ $role->id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <a href="{{ route('role.index') }}" class="btn btn-danger">Kembali</a>
                                <button type="submit" class="btn btn-primary w-md">Submit</button>
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
<!-- end row -->

@endsection

@section('script')
<script>
    $(document).ready(function () {
        const form = $('#editUserForm');
        const userId = window.location.pathname.split('/')[2];

        // Prefill data user
        $.get(`/users/${userId}`, function (res) {
            form.find('[name="name"]').val(res.data.name);
            const role = res.data.roles[0]?.name || ''; // Ambil role pertama atau kosong
            form.find('[name="role"]').val(role);
        });

        form.on('submit', function (e) {
            e.preventDefault();

            const formData = {
                role: form.find('[name="role"]').val(),
                _method: 'PUT', // Gunakan PUT untuk update
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: `/role/${userId}/update`, // Pastikan endpoint ini sesuai
                method: 'POST', // POST dengan _method untuk kompatibilitas Laravel
                data: formData,
                success: function (response) {
                    alert(response.message);
                    window.location.href = '/role';
                },
                error: function (xhr) {
                    alert('Gagal update role: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });
    });
</script>
@endsection
