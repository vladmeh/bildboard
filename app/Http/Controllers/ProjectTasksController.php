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
        $this->validate(request(), ['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }
}
