<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        // Insérer les associations rôle-permission
        DB::table('role_has_permissions')->insertOrIgnore([
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 2],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 2],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 2],
            ['permission_id' => 9, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 2],
            ['permission_id' => 10, 'role_id' => 1],
            ['permission_id' => 10, 'role_id' => 2],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],
        ]);
    }
}
