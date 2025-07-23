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
    @slot('title') Form Tambah User @endslot
    @endcomponent

    <div class="row">

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Tambah User</h4>

                    <form id="createUserForm">
                        <div class="row mb-4">
                            <label for="name" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" placeholder="Masukan Nama">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" placeholder="Masukan Email">
                            </div>
                        </div>
                        {{-- <div class="row mb-4">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" placeholder="Masukan Password">
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

    <script type="text/javascript">
        $(document).ready(function() {
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault(); // Hindari reload form

           

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('users.store') }}", // Pastikan route ini sesuai
                method: "POST",
                data: $(this).serialize(),

                    // role: $('#horizontal-role-select').val() // jika pakai r
                success: function(response) {
                    alert('User berhasil ditambahkan!');
                    $('#createUserForm')[0].reset(); // Kosongkan form
                    window.location.href = "{{ route('users.index') }}"; // Kembali ke index
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        for (let key in errors) {
                            msg += errors[key][0] + '\n';
                        }
                        alert(msg); // Tampilkan validasi
                    } else {
                        alert('Terjadi kesalahan saat menambahkan user.');
                    }
                }
            });
        });
    });
    </script>

    @endsection
