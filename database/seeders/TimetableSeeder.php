<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentTimetable;
use App\Models\User;
use App\Models\Subject;
use App\Models\Day;
use App\Models\Hall;
use App\Models\Group;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $dayIds = Day::pluck('id')->toArray();
        $hallIds = Hall::pluck('id')->toArray();
        $groupIds = Group::pluck('id')->toArray();

        if (empty($userIds) || empty($subjectIds) || empty($dayIds) || empty($hallIds) || empty($groupIds)) {
            $this->command->warn('⚠️ Cannot seed timetable: one or more related tables are empty.');
            return;
        }

        // Example: 5 timetable entries
        for ($i = 0; $i < 5; $i++) {
            StudentTimetable::create([
                'user_id' => $userIds[array_rand($userIds)],
                'subject_id' => $subjectIds[array_rand($subjectIds)],
                'day_id' => $dayIds[array_rand($dayIds)],
                'hall_id' => $hallIds[array_rand($hallIds)],
                'lecturer_group_id' => $groupIds[array_rand($groupIds)],
                'time_from' => '08:00 AM',
                'time_to' => '10:00 AM',
            ]);
        }

        $this->command->info('✅ StudentTimetable seeded successfully!');
    }
}
