<?php

namespace Tests\Unit;

use App\Http\Controllers\JobsController;
use App\Models\Job;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class JobsControllertest extends TestCase
{
    use SoftDeletes;
    use RefreshDatabase;

    private $basicValidData;
    private $controller;

    protected function setUp(): void
    {
        $this->basicValidData = [
            "job_type" => "professional",
            "function_name" => "Test job",
            "start_date" => "2018-09-23",
            "responsibilities" => "Test 1, Test 2",
            "company_id" => "1"
        ];

        $this->controller = new JobsController();

        parent::setUp();
    }

    public function testBasicIndexFunction()
    {
        $response = $this->call('GET', '/jobs');
        $this->assertEquals(200, $response->getStatusCode());
    }

    // TODO: FIX TESTS
    // public function testBasicStoreFunction()
    // {
    //     $response = $this->call('POST', '/jobs', $this->basicValidData);

    //     $this->assertEquals(302, $response->getStatusCode());
    //     $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

    //     $this->assertNotNull($job);

    //     $this->assertEquals($this->basicValidData['company_id'], $job->company->id);
    // }
}
