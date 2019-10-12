<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Job;
use App\Models\Company;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {

    $company = Company::find(1) ?? Company::create([
        'name' => $faker->company,
        'city' => $faker->city,
        'url' => $faker->url
    ]);

    return [
        'user_id' => 1,
        'company_id' => $company->id,
        'job_type' => random_int(1, 4),
        'function_name' => $faker->jobTitle,
        'start_date' => $faker->date(),
        'end_date' => random_int(0, 1) > 0 ? $faker->date() : null,
        'responsibilities' => $faker->word,
    ];
});
