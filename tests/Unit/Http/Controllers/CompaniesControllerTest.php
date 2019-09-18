<?php

namespace Tests\Unit;

use App\Http\Controllers\CompaniesController;
use App\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
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

    public function testCreateNewCompanyWithNewData()
    {
        $name = $this->faker->company;
        $city = $this->faker->city;
        $url = $this->faker->url;

        $requestData = [
            'company_id' => 'new_company',
            'company_name' => $name,
            'company_city' => $city,
            'company_url' => $url
        ];

        $response = $this->controller->createNewCompany($requestData);

        $company = factory(Company::class)->make([
            'name' => $name,
            'city' => $city,
            'url' => $url
        ]);

        $this->assertEquals($response->name, $company->name);
        $this->assertNotNull($response->id);
    }

    public function testCreateNewCompanyUniqueConstraint()
    {
        $name = $this->faker->company;
        $city = $this->faker->city;
        $url = $this->faker->url;

        $requestData = [
            'company_id' => 'new_company',
            'company_name' => $name,
            'company_city' => $city,
            'company_url' => $url
        ];

        $this->expectException(QueryException::class);

        $this->controller->createNewCompany($requestData);

        factory(Company::class)->create([
            'name' => $name,
            'city' => $city,
            'url' => $url
        ]);
    }
}
