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

        return redirect('login')->with('error', 'Please log in.');
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
            ['all_tags' => array_values($tags->toArray())]
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
        $this->doValidation($request);
        $job = new Job();
        $this->insertOrUpdate($job, $request);
        $this->saveTags($job, $request->get('tags'));

        return redirect('/jobs')->with('success', 'Job created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return redirect('/jobs');
        }

        // Check for correct user
        if (auth()->user() === null || (int) auth()->user()->id !== (int) $job->user_id) {
            return redirect('login')->with('error', 'Please log in.');
        }

        $companies = Company::all();
        $tags = Tag::all()->pluck('name', 'id');
        $company = $job->company;

        $formData = array_merge(
            ['job_form' => $this->getFormData(config('forms.job'), $job)],
            ['company_form' => $this->getFormData(config('forms.company'), $company)],
            ['companies' => array_values($companies->toArray())],
            ['tags' => array_values($job->tags->pluck('name')->toArray())],
            ['all_tags' => array_values($tags->toArray())]
        );

        return view('jobs.edit')->with(['formData' => $formData, 'job' => $job]);
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
        $this->doValidation($request);
        $job = Job::find($id);
        $this->insertOrUpdate($job, $request);

        $this->saveTags($job, $request->get('tags'));

        return redirect('home')->with('success', 'Job updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return redirect('/jobs');
        }

        // Check for correct user
        if (auth()->user() === null || (int) auth()->user()->id !== (int) $job->user_id) {
            return redirect('login')->with('error', 'Please log in.');
        }

        if ($job->delete()) {
            return redirect('/jobs')->with('success', 'Job removed');
        } else {
            return redirect('/jobs')->with('error', 'Job can\'t be removed');
        }
    }

    private function doValidation(Request $request)
    {
        return $this->validate($request, [
            'job_type' => ['required', 'numeric', 'digits_between:1,4'],
            'function_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'responsibilities' => ['required'],
            'company_id' => ['required'],
            'company_name' => ['required_if:company_id,==,new_company'],
            'company_city' => ['required_if:company_id,==,new_company'],
            'company_url' => ['required_if:company_id,==,new_company'],
            'tags' => ['sometimes', 'array']
        ]);
    }

    private function insertOrUpdate(Job &$job, Request $request)
    {
        $company = $this->companiesController->getCompanyFromRequest($request->all());
        $job->company()->associate($company);
        $job->user()->associate(auth()->user());
        $job->job_type = $request->get('job_type');
        $job->function_name = $request->get('function_name');
        $job->start_date = $request->get('start_date');
        $job->end_date = $request->get('end_date');
        $job->responsibilities = $request->get('responsibilities');
        $job->save();
    }

    private function saveTags(Job &$job, array $tagNames = null)
    {
        if ($tagNames) {
            $tagIds = $this->tagsController->createTags($tagNames)->pluck('id');
            $job->tags()->sync($tagIds);
            $job->save();
        }
    }


    private function getFormData($form, $object = null)
    {
        $formData = $form;

        foreach ($formData as $formKey => $input) {
            foreach ($input as $key => $value) {
                if ($key === 'name' && $object) {
                    $formData[$formKey]['value'] = $object->$value;
                }
                if ($key === 'options') {
                    $formData[$formKey][$key] = config('forms.selects.' . $value);
                }
            }
        }

        return $formData;
    }

    private function checkUser()
    {
        return auth()->user();
    }
}
