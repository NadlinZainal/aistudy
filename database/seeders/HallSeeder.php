<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hall;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $halls = [
            ['lecture_hall_name' => 'Dewan Kuliah 12', 'lecture_hall_place' => 'Block A, Level 1'],
            ['lecture_hall_name' => 'Dewan Seminar 3B', 'lecture_hall_place' => 'Block B, Level 2'],
            ['lecture_hall_name' => 'Dewan Seminar 2A', 'lecture_hall_place' => 'Block C, Level 3'],
            ['lecture_hall_name' => 'Audi', 'lecture_hall_place' => 'Block D, Level 2'],
            ['lecture_hall_name' => 'Dewan Kuliah 6', 'lecture_hall_place' => 'Block E, Level 1'],
        ];

        foreach ($halls as $hall) {
            Hall::firstOrCreate($hall);
    }
}
}

