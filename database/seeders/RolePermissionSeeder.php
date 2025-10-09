<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        // Limpiar cache de permisos antes de crear
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Crear permisos
        Permission::create(['name' => 'users.view']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.delete']);

        Permission::create(['name' => 'filament.access']); // permiso para acceder al panel (opcional)

        // Crear rol y asignar permisos
        $admin = Role::create(['name' => 'admin']);
        $usuario = Role::firstOrCreate(['name' => 'usuario']);
        $admin->givePermissionTo(Permission::all());

        // Usuario super-admin ejemplo (ajusta email/clave)
        $u = User::firstOrCreate(
            ['email' => 'admin@tuapp.test'],
            ['name' => 'Admin', 'password' => bcrypt('secret')]
        );
        $u->assignRole('admin');

        // Reset cache
        \Artisan::call('permission:cache-reset');
    }
}
