<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = now();

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Admin',       'slug' => 'admin',       'level' => 2,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager',     'slug' => 'manager',     'level' => 3,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Advanced User', 'slug' => 'adv_user',   'level' => 4,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Employee',    'slug' => 'employee',    'level' => 5,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Examinee',    'slug' => 'examinee',    'level' => 6,  'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['slug' => $r['slug']], $r);
        }

        $permisions = [
            ['name' => 'List', 'slug' => 'list', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View', 'slug' => 'view', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Create', 'slug' => 'create', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update', 'slug' => 'update', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete', 'slug' => 'delete', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($permisions as $p) {
            DB::table('permissions')->updateOrInsert(['slug' => $p['slug']], $p);
        }

        $super_admin_user = [
            'name'     => 'Super Admin',
            'email'    => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role'     => 'super_admin',
        ];

        DB::table('users')->updateOrInsert(['email' => $super_admin_user['email']], $super_admin_user);
    }
}
