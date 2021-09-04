<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'admin', 
            'last_name' => 'admin', 
            'email' => 'admin@zotter.at',
            'password' => Hash::make('preview'),
            'role_id' => 1,
        ]);

     }
}
