<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enum\Permissions;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 🔐 Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $doctor     = Role::firstOrCreate(['name' => 'Doctor']);
        $reception  = Role::firstOrCreate(['name' => 'Receptionist']);

        // 🧩 Assign permissions
        $superAdmin->syncPermissions(Permission::all());

        $doctor->syncPermissions([
            Permissions::VIEW_PATIENTS->value,
            Permissions::UPDATE_PATIENTS->value,
            Permissions::VIEW_APPOINTMENTS->value,
            Permissions::UPDATE_APPOINTMENTS->value,
            Permissions::VIEW_PRESCRIPTIONS->value,
        ]);

        $reception->syncPermissions([
            Permissions::VIEW_APPOINTMENTS->value,
            Permissions::CREATE_APPOINTMENTS->value,
            Permissions::VIEW_PATIENTS->value,
        ]);

        $this->command->info('✅ Roles & permissions assigned successfully.');
    }
}
