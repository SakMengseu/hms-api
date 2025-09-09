<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Create permissions
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'edit posts']);

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'administrator']);
        $admin->givePermissionTo(['view posts', 'edit posts']);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('view posts');

        // Assign role to a user (example)
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole('administrator');
        }
    }
}
