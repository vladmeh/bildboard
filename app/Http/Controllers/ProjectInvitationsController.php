<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Project;
use App\User;
use Illuminate\Http\RedirectResponse;

class ProjectInvitationsController extends Controller
{
    /**
     * @param Project $project
     * @param ProjectInvitationRequest $request
     * @return RedirectResponse
     */
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $user = User::whereEmail(request('email'))->first();
        $project->invite($user);

        return redirect($project->path());
    }
}
