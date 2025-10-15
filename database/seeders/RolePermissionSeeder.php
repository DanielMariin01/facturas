<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache antes de crear
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ==========================
        // 🧱 Crear permisos
        // ==========================
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'reports.view',
            'filament.access',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ==========================
        // 👑 Crear roles
        // ==========================
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $usuario = Role::firstOrCreate(['name' => 'usuario', 'guard_name' => 'web']);
        $gerencia = Role::firstOrCreate(['name' => 'gerencia', 'guard_name' => 'web']);

        // ==========================
        // 🔑 Asignar permisos a roles
        // ==========================
        $admin->syncPermissions(Permission::all());

        $gerencia->syncPermissions([
            'reports.view',
        ]);

        // ==========================
        // 👤 Crear usuario admin
        // ==========================
        $user = User::firstOrCreate(
            ['email' => 'admin@tuapp.test'],
            ['name' => 'Admin', 'password' => bcrypt('secret')]
        );

        $user->assignRole($admin);

        // ==========================
        // 🧹 Reset cache
        // ==========================
        Artisan::call('permission:cache-reset');

        $this->command->info('Roles, permisos y usuario admin creados correctamente ✅');
    }
}
