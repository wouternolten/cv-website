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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mockery;

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
        $tags = factory(Tag::class, 2)->create();

        $this->basicValidData['company_id'] = $company->id;
        $this->basicValidData['tags'] = $tags->pluck('name')->toArray();

        $this->companiesController->shouldReceive('getCompanyFromRequest')->andReturn($company)->once();
        $this->tagsController->shouldReceive('createTags')->andReturn($tags)->once();

        $request = Request::create('/jobs', 'POST', $this->basicValidData);

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
        $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

        $this->assertNotNull($job);
        $this->assertEquals($company->id, $job->company->id);
        $this->assertEquals($this->user->id, $job->user_id);
        $this->assertEquals(2, count($job->tags));
    }

    public function testStoreFunctionInvalidRequirements()
    {
        $invalidData = $this->basicValidData;
        $invalidData['job_type'] = null;

        $this->expectException(ValidationException::class);

        $request = Request::create('/jobs', 'POST', $invalidData);
        $this->controller->store($request);
    }

    public function testStoreFunctionNewInvalidCompany()
    {
        $invalidData = $this->basicValidData;
        $invalidData['company_id'] = 'new_company';

        $this->expectException(ValidationException::class);

        $request = Request::create('/jobs', 'POST', $invalidData);
        $this->controller->store($request);
    }

    public function testStoreFunctionWithoutTags()
    {
        $this->be($this->user);
        $company = factory(Company::class)->create();

        $this->basicValidData['company_id'] = $company->id;

        $this->companiesController->shouldReceive('getCompanyFromRequest')->andReturn($company)->once();

        $request = Request::create('/jobs', 'POST', $this->basicValidData);

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
        $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

        $this->assertNotNull($job);
        $this->assertEquals($company->id, $job->company->id);
        $this->assertEquals($this->user->id, $job->user_id);
        $this->assertEquals(0, count($job->tags));
    }

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
        $job->save();
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

    public function testUpdateValidJob()
    {
        $this->be($this->user);
        $job = factory(Job::class)->create();
        $job->user_id = (int) $this->user->id;
        $data = [
            "job_type" => $job->job_type,
            "function_name" => $job->function_name . '1',
            "start_date" => $job->start_date,
            "responsibilities" => $job->responsibilities,
            "company_id" => $job->company->id,
            "tags" => $job->tags->pluck('name')->toArray()
        ];

        $this->companiesController->shouldReceive('getCompanyFromRequest')->andReturn($job->company)->once();

        $request = Request::create('/jobs', 'PUT', $data);
        $response = $this->controller->update($request, $job->id);

        $newJob = Job::find($job->id);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertNotNull($job);
        $this->assertNotEquals($newJob->function_name, $job->function_name);
        $this->assertEquals($data['function_name'], $newJob->function_name);
    }

    public function testUpdateInvalidRequirements()
    {
        $job = factory(Job::class)->create();
        $invalidData = $this->basicValidData;
        $invalidData['job_type'] = null;

        $this->expectException(ValidationException::class);

        $request = Request::create('/jobs', 'POST', $invalidData);
        $this->controller->update($request, $job->id);
    }

    public function testUpdateNewInvalidCompany()
    {
        $job = factory(Job::class)->create();
        $invalidData = $this->basicValidData;
        $invalidData['company_id'] = 'new_company';

        $this->expectException(ValidationException::class);

        $request = Request::create('/jobs', 'POST', $invalidData);
        $this->controller->update($request, $job->id);
    }

    public function testUpdateWithoutTags()
    {
        $this->be($this->user);
        $company = factory(Company::class)->create();
        $old_job = factory(Job::class)->create();

        $this->basicValidData['company_id'] = $company->id;

        $this->companiesController->shouldReceive('getCompanyFromRequest')->andReturn($company)->once();

        $request = Request::create('/jobs', 'POST', $this->basicValidData);

        $response = $this->controller->store($request, $old_job->id);

        $this->assertEquals(302, $response->getStatusCode());
        $job = Job::where('job_type', $this->basicValidData['job_type'])->first();

        $this->assertNotNull($job);
        $this->assertEquals($company->id, $job->company->id);
        $this->assertEquals($this->user->id, $job->user_id);
        $this->assertEquals(0, count($job->tags));
    }
}
