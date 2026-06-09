<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['name' => 'Client 1', 'email' => 'client1@example.com'],
            ['name' => 'Client 2', 'email' => 'client2@example.com'],
            ['name' => 'Client 3', 'email' => 'client3@example.com'],
            // Add more clients as needed
        ];

        // Insert the data into the clients table
        foreach ($clients as $client) {
            DB::table('clients')->insert($client);
        }
    }
}
