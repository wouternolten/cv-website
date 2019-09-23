<?php

use App\Models\Job;
use App\Models\JobType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $jobs = Job::all();
        $job_types = $this->storeJobTypes($jobs);

        $this->setBaseLine();

        Schema::create('job_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name');
            $table->timestamps();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('job_type');
            $table->unsignedBigInteger('job_type_id');
        });

        $seeder = new JobTypeSeeder();

        $seeder->run();

        foreach ($jobs as $job) {
            $job->job_type_id = 1;
            $job->save();
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->foreign('job_type_id')
                ->references('id')
                ->on('job_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $jobs = Job::all();
        $job_types = [];

        foreach ($jobs as $job) {
            $job_types[] = $job->jobType;
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign('jobs_job_type_id_foreign');
            $table->dropColumn('job_type_id');
            $table->string('job_type');
        });

        for ($i = 0; $i < count($jobs); $i++) {
            $job->jobType = $job_types[$i]->name;
            $job->save();
        }

        Schema::dropIfExists('job_types');
    }

    private function setBaseLine()
    {
        Schema::dropIfExists('job_types');

        foreach (['job_type_id', 'job_type'] as $column) {
            if (Schema::hasColumn('jobs', $column)) {
                Schema::table('jobs', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('job_type');
        });
    }

    private function storeJobTypes($jobs)
    {
        $job_types = [];

        $job_types_array = [
            'Professional',
            'Internship',
            'Voluntary',
            'Hobby'
        ];

        $job_types = [];

        foreach ($jobs as $job) {
            $job_types[] = array_search($job->job_type, $job_types_array);
        }

        return $job_types;
    }
}
