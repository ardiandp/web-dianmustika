<x-layouts.admin title="Edit Role">
    <x-admin.page-header title="Edit Role — {{ $role->name }}" />

    <div class="card">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
                <x-admin.input name="name" label="Nama Role" required :value="$role->name" help="Huruf kecil, angka, dash/underscore." />

                <label>Permissions</label>
                @foreach ($permissions as $group => $perms)
                    <div class="card card-outline card-secondary mb-2">
                        <div class="card-header p-2"><h6 class="card-title text-sm">{{ ucfirst($group) }}</h6></div>
                        <div class="card-body p-2">
                            <div class="row">
                                @foreach ($perms as $perm)
                                    <div class="col-md-4 mb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="perm-{{ $perm->id }}" name="permissions[]" value="{{ $perm->name }}" @checked(in_array($perm->name, old('permissions', $rolePermissions)))>
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
