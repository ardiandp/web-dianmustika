<x-layouts.admin title="Tambah User">
    <x-admin.page-header title="Tambah User" />

    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="card-body">
                <x-admin.input name="name" label="Nama Lengkap" required :value="old('name')" />
                <x-admin.input name="email" label="Email" type="email" required :value="old('email')" />
                <x-admin.select name="role" label="Role (Legacy)" required help="Sinkron ke Role Spatie.">
                    <option value="">— Pilih —</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                    <option value="staff" @selected(old('role') == 'staff')>Staff</option>
                </x-admin.select>

                <div class="form-group">
                    <label>Roles (RBAC)</label>
                    <small class="form-text text-muted mb-1">Pilih role Spatie. Bisa lebih dari satu. Jika kosong, pakai Role Legacy di atas.</small>
                    <div class="row">
                        @foreach ($roles as $r)
                            <div class="col-md-4 mb-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="role-{{ $r->id }}" name="roles[]" value="{{ $r->name }}" @checked(in_array($r->name, old('roles', [])))>
                                    <label class="custom-control-label" for="role-{{ $r->id }}">{{ $r->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <x-admin.input name="password" label="Password" type="password" required />
                <x-admin.input name="password_confirmation" label="Konfirmasi Password" type="password" required />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="true" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.users.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
