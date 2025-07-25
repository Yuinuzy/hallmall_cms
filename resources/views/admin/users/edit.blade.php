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

        <!-- Responsive datatable examples -->
        <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
            rel="stylesheet" type="text/css" />
    @endsection

    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')
                User Management
            @endslot
            @slot('title')
                Form Edit User
            @endslot
        @endcomponent

        <div class="row">

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Edit User</h4>

                        <form id="editForm">
                            <div class="row mb-4">
                                <label for="name" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Masukan Nama">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Masukan Email">
                                </div>
                            </div>
                            {{-- <div class="row mb-4">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Masukan Password">
                                </div>
                            </div> --}}

                            {{-- Dropdown Role --}}
                            <div class="row mb-4">
                                <label for="role" class="col-sm-3 col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <select name="role" class="form-select" id="role">
                                        <option selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-danger">Kembali</a>
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
        <!-- end row -->
    @endsection


    @section('script')
        <script type="text/javascript">
            $(document).ready(function() {
                const path = window.location.pathname;
                const id = path.split("/")[2];

                // Ambil data user
                $.ajax({
                    url: `{{ url('/users/${id}/get_data') }}`,
                    method: "GET",
                    success: function(response) {
                        if (response.status === true) {
                            $("#name").val(response.data.name);
                            $("#email").val(response.data.email);
                            $("#role").val(response.data.role);
                        } else {
                            window.location.href = "{{ route('users.index') }}";
                        }
                    },
                    error: function(error) {
                        console.error(error);
                        showAlert("Gagal mengambil data.", "error", "Error");
                    }
                });

                // Submit form edit
                $("#editForm").submit(function(e) {
                    e.preventDefault();

                    $("#editForm .is-invalid").removeClass('is-invalid');
                    $("#editForm .invalid-feedback").remove();

                    let submitButton = $("#editForm button[type='submit']");
                    submitButton.prop("disabled", true).html(
                        `<i class="fa fa-spinner fa-spin"></i> Menyimpan...`);

                    let formSerialized = $(this).serialize() + '&_method=PUT';

                    $.ajax({
                        url: `{{ url('/users/${id}') }}`,
                        method: "POST",
                        data: formSerialized,
                        success: function(res) {
                            submitButton.prop("disabled", false).html(
                                `<i class="fa fa-save"></i> Simpan`);

                            if (res.status === true) {
                                showAlert(res.message || "User berhasil diperbarui!", "success",
                                    "Berhasil").then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('users.index') }}";
                                    }
                                });
                            } else {
                                showAlert(res.message || "Gagal memperbarui user", "error",
                                "Gagal");
                            }
                        },
                        error: function(error) {
                            submitButton.prop("disabled", false).html(
                                `<i class="fa fa-save"></i> Simpan`);

                            if (error.status === 422) {
                                let errors = error.responseJSON.errors;

                                $.each(errors, function(key, value) {
                                    let input = $(`[name="${key}"]`);
                                    input.addClass('is-invalid');

                                    input.next('.invalid-feedback').remove();

                                    input.after(
                                        `<span class="invalid-feedback d-block">${value[0]}</span>`
                                        );
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
