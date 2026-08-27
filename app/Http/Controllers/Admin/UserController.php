<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use LogsActivity;
    public function index(): View
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,staff'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active'),
            'email_verified_at' => now(),
        ]);

        $rolesToSync = $request->input('roles', [$validated['role']]);
        $user->syncRoles($rolesToSync);

        $this->logActivity('created', $user, "Membuat user \"{$user->name}\"");

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$validated['name']}\" berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,staff'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        $rolesToSync = $request->input('roles', [$validated['role']]);
        $user->syncRoles($rolesToSync);

        $changes = $this->diffChanges($user);
        $this->logActivity('updated', $user, "Memperbarui user \"{$user->name}\"", $changes);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $this->logActivity('deleted', $user, "Menghapus user \"{$user->name}\"");

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
