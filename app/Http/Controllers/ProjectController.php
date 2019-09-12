<?php

namespace App\Http\Controllers;

use App\Project;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    /**
     * @param Project $project
     * @return View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * @return View
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
        $project = auth()->user()->projects()->create($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * @return array
     * @throws ValidationException
     */
    private function validateRequest(): array
    {
        return $this->validate(request(), [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable',
        ]);
    }

    /**
     * @param Project $project
     * @return View
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * @param Project $project
     * @return Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Project $project)
    {
        $this->authorize('update', $project);
        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * @param Project $project
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Project $project)
    {
        $this->authorize('update', $project);
        $project->delete();

        return redirect('/projects');
    }
}
