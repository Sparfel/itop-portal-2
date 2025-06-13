<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        // Insérer ou mettre à jour les permissions
        DB::table('permissions')->updateOrInsert(
            ['id' => 3],
            [
                'name' => 'update_opened_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 4],
            [
                'name' => 'list_requests',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 5],
            [
                'name' => 'upload_attachment',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 6],
            [
                'name' => 'create_new_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 7],
            [
                'name' => 'store_new_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 8],
            [
                'name' => 'done_creation_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 9],
            [
                'name' => 'browse_closed_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 10],
            [
                'name' => 'view_closed_request',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 11],
            [
                'name' => 'permissions',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 11],
            [
                'name' => 'browse_admin',
                'guard_name' => 'web',
            ]
        );

        DB::table('permissions')->updateOrInsert(
            ['id' => 12],
            [
                'name' => 'logs',
                'guard_name' => 'web',
            ]
        );
    }
}
