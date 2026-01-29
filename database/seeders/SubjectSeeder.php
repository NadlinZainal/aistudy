<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['subject_code' => 'ITT626', 'subject_name' => 'Back End Technology'],
            ['subject_code' => 'ITT593', 'subject_name' => 'Digital Forensics'],
            ['subject_code' => 'ITT550', 'subject_name' => 'Network Design & Management'],
            ['subject_code' => 'CSP600', 'subject_name' => 'Project Formulation'],
            ['subject_code' => 'ICT602', 'subject_name' => 'Mobile Technology & Development'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate($subject);
    }
}
}
