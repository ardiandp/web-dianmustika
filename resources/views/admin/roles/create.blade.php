<x-layouts.admin title="Tambah Role">
    <x-admin.page-header title="Tambah Role" />

    <div class="card">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="card-body">
                <x-admin.input name="name" label="Nama Role" required help="Huruf kecil, angka, dash/underscore. Contoh: editor, staff-konten" />

                <label>Permissions</label>
                <small class="form-text text-muted mb-2">Centang permission yang diberikan ke role ini.</small>

                @foreach ($permissions as $group => $perms)
                    <div class="card card-outline card-secondary mb-2">
                        <div class="card-header p-2"><h6 class="card-title text-sm">{{ ucfirst($group) }}</h6></div>
                        <div class="card-body p-2">
                            <div class="row">
                                @foreach ($perms as $perm)
                                    <div class="col-md-4 mb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="perm-{{ $perm->id }}" name="permissions[]" value="{{ $perm->name }}" @checked(in_array($perm->name, old('permissions', [])))>
                                            <label class="custom-control-label small" for="perm-{{ $perm->id }}">{{ $perm->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.roles.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
