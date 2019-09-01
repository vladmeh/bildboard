<?php

namespace App\Observers;

use App\Activity;
use App\Project;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param Project $project
     * @return void
     */
    public function created(Project $project)
    {
        $this->recordActivity($project, 'created');
    }

    /**
     * @param Project $project
     * @param string $type
     */
    private function recordActivity(Project $project, string $type)
    {
        Activity::create([
            'project_id' => $project->id,
            'description' => $type,
        ]);
    }

    /**
     * Handle the project "updated" event.
     *
     * @param Project $project
     * @return void
     */
    public function updated(Project $project)
    {
        $this->recordActivity($project, 'updated');
    }
}
