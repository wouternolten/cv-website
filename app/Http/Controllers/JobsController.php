<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    private $companiesController;
    private $tagsController;

    public function __construct(CompaniesController $companiesController, TagsController $tagsController)
    {
        $this->companiesController = $companiesController;
        $this->tagsController = $tagsController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($user = $this->checkUser()) {
            $jobs = $user->jobs()
                ->orderBy('job_type', 'asc')
                ->orderBy('end_date', 'desc')
                ->orderBy('start_date', 'desc')
                ->get();

            return view('jobs.index')->with('jobs', $jobs);
        }

        return redirect('login');
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
        $tags = Tag::all()->pluck('name', 'id');

        $formData = array_merge(
            ['job_form' => $this->getFormData(config('forms.job'))],
            ['company_form' => $this->getFormData(config('forms.company'))],
            ['companies' => array_values($companies->toArray())],
            ['tags' => array_values($tags->toArray())]
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
        Validator::make($request->all(), [
            'job_type' => ['required', 'digits_between:1,4'],
            'function_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'responsibilities' => ['required'],
            'company_id' => ['required'],
            'company_name' => ['required_if:company_id,==,new_company'],
            'company_city' => ['required_if:company_id,==,new_company'],
            'company_url' => ['required_if:company_id,==,new_company'],
            'tags' => ['sometimes', 'string', 'unique:name']
        ]);

        $company = $this->companiesController->createNewCompany($request->all());
        $tagIds = $this->tagsController->createTags($request->get('tags'))->pluck('id');

        $job = new Job();
        $job->company()->associate($company);
        $job->user()->associate(auth()->user());
        $job->job_type = $request->get('job_type');
        $job->function_name = $request->get('function_name');
        $job->start_date = $request->get('start_date');
        $job->end_date = $request->get('end_date');
        $job->responsibilities = $request->get('responsibilities');
        $job->save();

        $job->tags()->sync($tagIds);
        $job->save();

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

    private function checkUser()
    {
        return auth()->user();
    }
}
