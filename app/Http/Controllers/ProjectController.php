<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    /**
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * @return Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        $attributes = $this->validate(request(), [
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3',
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    /**
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $project->update(request(['notes']));

        return redirect($project->path());
    }
}
