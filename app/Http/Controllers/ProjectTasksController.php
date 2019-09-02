<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ProjectTasksController extends Controller
{
    /**
     * @param Project $project
     * @return Response
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        $this->validate(request(), ['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    /**
     * @param Project $project
     * @param Task $task
     * @return Response
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $task->update($this->validate(request(), ['body' => 'required']));

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}
