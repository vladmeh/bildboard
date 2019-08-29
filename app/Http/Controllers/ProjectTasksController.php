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
     * @throws ValidationException
     */
    public function update(Project $project, Task $task)
    {
        if (auth()->user()->isNot($task->project->owner)) {
            abort(403);
        }

        $this->validate(request(), ['body' => 'required']);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed'),
        ]);

        return redirect($project->path());
    }
}