<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'manage-dashboard',
            'manage-services',
            'manage-service-categories',
            'manage-packages',
            'manage-locations',
            'manage-galleries',
            'manage-testimonials',
            'manage-articles',
            'manage-article-categories',
            'manage-faqs',
            'manage-users',
            'manage-settings',
            'manage-activity-logs',
            'manage-roles',
        ];

        foreach ($modules as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        $admin->givePermissionTo(Permission::all());

        // Staff: konten only — layanan, kategori layanan, paket, artikel, kategori artikel, plus view dashboard/FAQ/galeri/lokasi/testimonial
        $staffPerms = [
            'manage-dashboard',
            'manage-services',
            'manage-service-categories',
            'manage-packages',
            'manage-articles',
            'manage-article-categories',
            'manage-locations',
            'manage-galleries',
            'manage-testimonials',
            'manage-faqs',
        ];
        $staff->syncPermissions($staffPerms);

        // Migrate existing users by users.role column
        foreach (User::all() as $user) {
            $roleName = $user->role === 'admin' ? 'admin' : 'staff';
            if (! $user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}
