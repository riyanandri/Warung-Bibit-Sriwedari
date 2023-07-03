<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configurations')->insert(
            [
                'id'                    => 1,
                'name'                  => 'Warung Bibit Sriwedari',
                'icon'                  => 'logo.png',
                'description'           => 'Menjual berbagai bibit tanaman dan buah-buahan',
                'email'                 => 'warungbibitsriwedari@gmail.com',
                'phone'                 => '081226834834',
                'address'               => 'Dsn Nglegok RT 01 RW 05, Sriwedari, Kec. Salaman, Kab. Magelang',
                'facebook'              => 'https://facebook.com/warungbibitsriwedari',
                'instagram'             => 'https://instagram.com/warungbibitsriwedari',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
           );
    }
}
