<?php

namespace Tests\Unit;

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\TagsController;
use App\Models\Company;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Mockery;
use Symfony\Component\HttpFoundation\RedirectResponse;

class JobsControllertest extends TestCase
{
    use SoftDeletes;
    use RefreshDatabase;

    private $basicValidData;
    private $controller;
    private $companiesController;
    private $tagsController;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basicValidData = [
            "job_type" => "1",
            "function_name" => "Test job",
            "start_date" => "2018-09-23",
            "responsibilities" => "Test 1, Test 2",
            "company_id" => "1"
        ];

        $this->companiesController = Mockery::mock(CompaniesController::class);
        $this->tagsController = Mockery::mock(TagsController::class);
        $this->controller = new JobsController($this->companiesController, $this->tagsController);
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

    public function testIndexFunctionOrderByJobTypeSort()
    {
        $this->be($this->user);

        $job_1 = factory(Job::class)->create([
            'job_type' => 1
        ]);

        $job_2 = factory(Job::class)->create([
            'job_type' => 2
        ]);

        $this->user->jobs()->saveMany([$job_1, $job_2]);

        $view = $this->controller->index();

        $this->assertEquals($job_1->id, $view->getData()['jobs'][0]->id);
        $this->assertEquals($job_2->id, $view->getData()['jobs'][1]->id);
    }

    public function testIndexFunctionOrderByEndDateSort()
    {
        $this->be($this->user);

        $job_1 = factory(Job::class)->create([
            'job_type' => 1,
            'end_date' => '2000-03-03'
        ]);

        $job_2 = factory(Job::class)->create([
            'job_type' => 1,
            'end_date' => '2000-02-02'
        ]);

        $this->user->jobs()->saveMany([$job_1, $job_2]);

        $view = $this->controller->index();

        $this->assertEquals($job_1->id, $view->getData()['jobs'][0]->id);
        $this->assertEquals($job_2->id, $view->getData()['jobs'][1]->id);
    }

    public function testIndexFunctionOrderByStartDateSort()
    {
        $this->be($this->user);

        $job_1 = factory(Job::class)->create([
            'job_type' => 1,
            'end_date' => null,
            'start_date' => '2000-03-03'
        ]);

        $job_2 = factory(Job::class)->create([
            'job_type' => 1,
            'end_date' => null,
            'start_date' => '2000-02-02'
        ]);

        $this->user->jobs()->saveMany([$job_1, $job_2]);

        $view = $this->controller->index();

        $this->assertEquals($job_1->id, $view->getData()['jobs'][0]->id);
        $this->assertEquals($job_2->id, $view->getData()['jobs'][1]->id);
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
        $tag = factory(Tag::class)->create();

        $this->companiesController->shouldReceive('createNewCompany')->andReturn($company)->once();
        $this->tagsController->shouldReceive('createTags')->andReturn($tag)->once();

        $request = Request::create('/store', 'POST', $this->basicValidData);

        $response = $this->controller->store($request);

        dd('tag');

        $this->assertEquals(302, $response->getStatusCode());
        $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

        $this->assertNotNull($job);
        $this->assertEquals($company->id, $job->company->id);
        $this->assertEquals($this->user->id, $job->user_id);
    }

    // public function testStoreFunctionInvalidRequirements()
    // {
    //     // TODO: implement function
    // }

    // public function testStoreFunctionNewInvalidCompany()
    // {
    //     // TODO: implement function
    // }

    public function testJobFunctionInvalidJob()
    {
        $response = $this->controller->edit(10);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testJobFunctionInvalidUser()
    {
        $job = factory(Job::class)->create();
        $response = $this->controller->edit($job->id);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testJobFunctionInvalidJobUserCombo()
    {
        $this->be($this->user);
        $job = factory(Job::class)->create();
        $job->user_id += 1;
        $response = $this->controller->edit($job->id);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testJobFunctionValidJobUserCombo()
    {
        $this->be($this->user);
        $job = factory(Job::class)->create();
        $job->user_id = (int) $this->user->id;
        $response = $this->controller->edit($job->id);

        $this->assertEquals(View::class, get_class($response));
        $this->assertTrue(strpos($response->getPath(), 'jobs/edit') !== false);
    }
}
