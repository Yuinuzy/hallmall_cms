@extends('layouts.master')

@section('title')
    Detail Role
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Role: {{ $role->name }}</h5>
            <a href="{{ route('role.index') }}" class="btn btn-sm btn-light">← Kembali</a>
        </div>
        <div class="card-body">
            <p><strong>Jumlah User:</strong> {{ $jumlahUser }}</p>

            <hr>
            <h6 class="mb-3">Permissions yang Dimiliki:</h6>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-4">
                        <div class="form-check mb-2">
                            <input 
                                type="checkbox" 
                                class="form-check-input permission-checkbox" 
                                data-role-id="{{ $role->id }}" 
                                data-permission-id="{{ $permission->id }}"
                                {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                            >
                            <label class="form-check-label">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
