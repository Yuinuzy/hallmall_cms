<form id="formEditInlineRole" data-id="{{ $role->id }}">
    @csrf
    <div class="mb-3">
        <label>Nama Role</label>
        <input type="text" class="form-control" name="name" value="{{ $role->name }}">
    </div>

    <div class="mb-3">
        <label>Permission</label><br>
        @foreach ($permissions as $permission)
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $permission->name }}"
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $permission->name }}</label>
            </div>
        @endforeach
    </div>


    <button type="submit" class="btn btn-sm btn-success">Simpan</button>
</form>

<script>
    $(document).on('submit', '#formEditInlineRole', function(e) {
        e.preventDefault();
        let form = $(this);
        let id = form.data('id'); // ambil id dari data-id
        let data = form.serialize();

        $.ajax({
            url: `/role/${id}/update`,
            method: 'POST',
            data: data + '&_method=PUT', // jika pakai method spoofing
            success: function(res) {
                if (res.status === true) {
                    showAlert(res.message, 'success', 'Berhasil').then(() => {
                        $('#dataTable').DataTable().ajax.reload(null, false);
                        $('#inline-edit-row-' + id)
                            .remove(); // pastikan id baris inline unik
                    });
                } else {
                    showAlert(res.message, 'error', 'Gagal');
                }
            },
            error: function(err) {
                showAlert('Terjadi kesalahan', 'error', 'Gagal');
            }
        });
    });
</script>
