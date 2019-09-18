<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    private function getFormData($form)
    {
        $formData = $form;

        foreach ($formData as $formKey => $input) {
            foreach ($input as $key => $value) {
                if ($key === 'options') {
                    $formData[$formKey][$key] = config('forms.selects.' . $value);
                }
            }
        }

        return $formData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $tags = Tag::all();

        $formData = array_merge(
            ['job_form' => $this->getFormData(config('forms.job'))],
            ['company_form' => $this->getFormData(config('forms.company'))],
            ['companies' => $companies->toArray()],
            ['tags' => $tags->toArray()]
        );

        return view('jobs.create')->with('formData', $formData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'job_type' => ['required', 'string'],
            'function_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'responsibilities' => ['required'],
            'company_id' => ['required'],
            'tags' => ['sometimes', 'string', 'unique:name']
        ]);

        $v->sometimes('company_name', 'required|max:500', function ($input) {
            return $input->company_id = 'new_company';
        });

        $v->sometimes('company_city', 'required|max:500', function ($input) {
            return $input->company_id = 'new_company';
        });

        $v->sometimes('company_url', 'required|url', function ($input) {
            return $input->company_id = 'new_company';
        });

        if (is_numeric($request->get('company_id'))) {
            $company = Company::find($request->get('company_id'));
        } else {
            $company = app('App\Http\Controllers\CompaniesController')->createNewCompany($request->all());
        }

        $job = new Job();
        $job->create($request->all());

        return redirect('/jobs')->with('success', 'Job created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
