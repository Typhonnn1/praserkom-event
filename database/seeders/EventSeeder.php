<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'title' => 'Jaya Konser',
                'category' => 'Musik',
                'ticket_price' => 200000,
                'description' => 'Konser jaya by pt jaya',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Seminar Keuangan',
                'category' => 'Seminar',
                'ticket_price' => 500000,
                'description' => 'Seminar by timothy ronald',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Big bang festival',
                'category' => 'Festival',
                'ticket_price' => 800000,
                'description' => 'Festival Rame banyak artis',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Galaxy Konser',
                'category' => 'Musik',
                'ticket_price' => 300000,
                'description' => 'Konser full band terkenal',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'A matter of time',
                'category' => 'Musik',
                'ticket_price' => 2000000,
                'description' => 'Konser laufey',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
