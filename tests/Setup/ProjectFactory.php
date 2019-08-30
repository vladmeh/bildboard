<?php


namespace Tests\Setup;


use App\Project;
use App\Task;
use App\User;

class ProjectFactory
{
    /**
     * @var int
     */
    protected $taskCount = 0;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param int $count
     * @return ProjectFactory
     */
    public function withTasks(int $count): self
    {
        $this->taskCount = $count;

        return $this;
    }

    /**
     * @param User $user
     * @return ProjectFactory
     */
    public function ownedBy(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Project
     */
    public function create(): Project
    {
        $project = factory(Project::class)->create([
            'owner_id' => $this->user ?? factory(User::class)
        ]);

        factory(Task::class, $this->taskCount)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}