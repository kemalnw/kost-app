<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roleNames = ['Regular User', 'Owner', 'Premium User'];
        foreach($roleNames as $role) {
            \App\Models\User\Role::firstOrCreate(['name' => $role]);
        }
    }
}
