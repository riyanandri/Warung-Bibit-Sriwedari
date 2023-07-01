<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);

        $user = User::create([
            'name' => 'admin',
            'email' => 'warungbibitsriwedari@gmail.com',
            'password' => Hash::make('bibitsriwedari@123')
        ]);
        $user->assignRole('admin');
    }
}
