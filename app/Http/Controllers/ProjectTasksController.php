<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ProjectTasksController extends Controller
{
    /**
     * @param Project $project
     * @return Response
     * @throws ValidationException
     */
    public function store(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        $this->validate(request(), ['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }
}
