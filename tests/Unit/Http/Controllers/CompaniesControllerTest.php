<?php

namespace Tests\Unit;

use App\Http\Controllers\CompaniesController;
use App\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompaniesControllerTest extends TestCase
{
    use SoftDeletes, RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        $this->controller = new CompaniesController();

        parent::setUp();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateNewCompanyWithCompanyId()
    {
        $company = factory(Company::class)->create();

        $requestData = ['company_id' => $company->id];

        $response = $this->controller->createNewCompany($requestData);

        $this->assertEquals($response->id, $company->id);
    }

    public function testCreateNewCompanyWithCompanyName()
    {
        $company = factory(Company::class)->create();

        $requestData = ['company_id' => 'new_company', 'company_name' => $company->name];

        $response = $this->controller->createNewCompany($requestData);

        $this->assertEquals($response->id, $company->id);
    }
}
