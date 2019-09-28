<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'company_id' => 1,
        'job_type' => random_int(1, 4),
        'function_name' => $faker->jobTitle,
        'start_date' => $faker->date(),
        'end_date' => random_int(0, 1) > 0 ? $faker->date() : null,
        'responsibilities' => $faker->word
    ];
});
