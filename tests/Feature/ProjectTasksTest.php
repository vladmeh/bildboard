<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_cannot_add_tasks_to_projects()
    {
        $project = factory(Project::class)->create();
        $this->post($project->path() . '/tasks')
            ->assertRedirect('login');
    }

    /**
     * @test
     */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->singIn();
        $project = factory(Project::class)->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /**
     * @test
     */
    public function only_the_owner_of_a_project_may_update_a_tasks()
    {
        $this->singIn();
        $project = factory(Project::class)->create();
        $task = $project->addTask('Test task');

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /**
     * @test
     */
    public function a_project_can_have_tasks()
    {
        $this->singIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);
        $this->get($project->path())
            ->assertSee('Test task');
    }

    /**
     * @test
     */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->singIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $task = $project->addTask('Test task');

        $attributes = [
            'body' => 'changed',
            'completed' => true,
        ];

        $this->patch($task->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /**
     * @test
     */
    public function a_task_requires_a_body()
    {
        $this->singIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory(Task::class)->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');

    }
}
