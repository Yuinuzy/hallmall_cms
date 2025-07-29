@extends('layouts.master')

@section('title')
    Tambah Permission ke Role
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Role
        @endslot
        @slot('title')
            Tambah Permission
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('roles.add.permissions.save', $role->id) }}" method="POST">
                        @csrf
                        @foreach ($permissions as $permission)
        <div class="mb-2 form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="permissions[]"
                value="{{ $permission->name }}"
                id="perm_{{ $permission->id }}"
                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
            >

            <label class="form-check-label" for="perm_{{ $permission->id }}">
                {{ $permission->name }}
            </label>
        </div>
    @endforeach

                        <button type="submit" class="mt-3 btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
