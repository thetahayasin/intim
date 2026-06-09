<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AssociatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate dummy data
        $associates = [
            [
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'name' => 'demo',
                'active' => true,
                'fts' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        // Insert data into the associates table
        DB::table('associates')->insert($associates);
    }
}
