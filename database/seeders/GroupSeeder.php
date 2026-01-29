<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group; 

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Group A', 'part' => 'Part 1'],
            ['name' => 'Group B', 'part' => 'Part 2'],
            ['name' => 'Group C', 'part' => 'Part 3'],
            ['name' => 'Group D', 'part' => 'Part 4'],
            ['name' => 'Group E', 'part' => 'Part 5'],
        ];

        foreach ($groups as $group) {
            Group::firstOrCreate($group);
        }
    }
}
