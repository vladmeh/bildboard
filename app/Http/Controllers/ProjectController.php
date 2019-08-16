<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    /**
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        $attributes = $this->validate(request(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        Project::create($attributes);

        return redirect('/projects');
    }
}
