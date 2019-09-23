<?php

namespace Tests\Unit;

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\JobsController;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;

class JobsControllertest extends TestCase
{
    use SoftDeletes;
    use RefreshDatabase;

    private $basicValidData;
    private $controller;
    private $companiesController;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basicValidData = [
            "job_type" => "professional",
            "function_name" => "Test job",
            "start_date" => "2018-09-23",
            "responsibilities" => "Test 1, Test 2",
            "company_id" => "1"
        ];

        $this->companiesController = Mockery::mock(CompaniesController::class);
        $this->controller = new JobsController($this->companiesController);
        $this->user = factory(User::class)->create();
    }

    public function testIndexFunctionValidUser()
    {
        $this->be($this->user);

        $view = $this->controller->index();
        $this->assertEquals('jobs.index', $view->getName());
    }

    public function testIndexFunctionWithJobs()
    {
        $this->be($this->user);
        $job = factory(Job::class)->create();

        $job->user()->associate($this->user);
        $job->save();

        $view = $this->controller->index();
        $this->assertEquals($job->id, $view->getData()['jobs'][0]->id);
    }

    public function testIndexFunctionInvalidUser()
    {
        $response = $this->controller->index();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testBasicStoreFunction()
    {
        $this->be($this->user);
        $company = factory(Company::class)->create();
        $this->companiesController->shouldReceive('createNewCompany')->andReturn($company)->once();
        $request = Request::create('/store', 'POST', $this->basicValidData);

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
        $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

        $this->assertNotNull($job);
        $this->assertEquals($company->id, $job->company->id);
        $this->assertEquals($this->user->id, $job->user_id);
    }
}
