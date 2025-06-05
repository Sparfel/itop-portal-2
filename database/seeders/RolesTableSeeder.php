<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        // Désactiver temporairement les timestamps automatiques
        Role::unguard();

        // Insérer ou mettre à jour les rôles
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Administrator',
                'guard_name' => 'web',
                'created_at' => null,
                'updated_at' => null,
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            [
                'name' => 'User',
                'guard_name' => 'web',
                'created_at' => null,
                'updated_at' => null,
            ]
        );

        // Réactiver les timestamps automatiques
        Role::reguard();
    }
}
