<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {

            User::create([
                'name'           => 'Emmanuel Lozachmeur',
                'first_name'           => 'Emmanuel',
                'Last_name'           => 'Lozachmeur',
                'email'          => 'emmanuel.lozachmeur@gmail.com',
                'password'       => bcrypt('password'),
                'remember_token' => Str::random(60)
            ]);
        }
    }
}
