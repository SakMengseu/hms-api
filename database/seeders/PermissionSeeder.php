<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enum\Permissions;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 🧠 Loop through all enum values
        foreach (Permissions::cases() as $permissionEnum) {
            Permission::firstOrCreate([
                'name' => $permissionEnum->value,
            ]);
        }

        $this->command->info('✅ Permissions seeded successfully from Enum.');
    }
}
