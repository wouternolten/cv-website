<?php

use App\Models\JobType;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $job_types = [
            'Professional',
            'Internship',
            'Voluntary',
            'Hobby'
        ];

        foreach ($job_types as $job_type) {
            $job_type = new JobType(['name' => $job_type]);
            $job_type->save();
        }
    }
}
