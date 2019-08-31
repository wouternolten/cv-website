<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(EducationsTableSeeder::class);
        $this->call(JobsTableSeeder::class);
    }
}
