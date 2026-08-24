<x-layouts.admin title="Edit User">
    <x-admin.page-header title="Edit User" />

    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="name" label="Nama Lengkap" required :value="old('name', $user->name)" />
                <x-admin.input name="email" label="Email" type="email" required :value="old('email', $user->email)" />
                <x-admin.select name="role" label="Role" required>
                    <option value="">— Pilih —</option>
                    <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                    <option value="staff" @selected(old('role', $user->role) == 'staff')>Staff</option>
                </x-admin.select>
                <x-admin.input name="password" label="Password" type="password" help="Kosongkan jika tidak ingin mengubah password." />
                <x-admin.input name="password_confirmation" label="Konfirmasi Password" type="password" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="old('is_active', $user->is_active)" />
            </div>
            <div class="card-footer">
                <x-admin.form-actions :cancel="route('admin.users.index')" />
            </div>
        </form>
    </div>
</x-layouts.admin>
