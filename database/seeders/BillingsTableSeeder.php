<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Billing;


class BillingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the data to be seeded
        $salesData = [
            [
                'client_id' => 1,
                'amount' => 100,
                'recursive' => false,
                'next_charge_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 2,
                'amount' => 150,
                'recursive' => true,
                'next_charge_date' => '2024-06-14',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 3,
                'amount' => 200,
                'recursive' => false,
                'next_charge_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 1,
                'amount' => 120,
                'recursive' => false,
                'next_charge_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more records as needed
        ];

        // Insert the data into the sales table
        foreach ($salesData as $data) {
            Billing::create($data);
        }
    }
}
