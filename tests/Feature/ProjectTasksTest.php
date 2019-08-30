<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
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

        $this->post($project->path() . '/tasks', $attributes = ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /**
     * @test
     */
    public function only_the_owner_of_a_project_may_update_a_tasks()
    {
        $this->singIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks()->first()->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /**
     * @test
     */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /**
     * @test
     */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks()->first()->path(), $attributes = [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /**
     * @test
     */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = factory(Task::class)->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');

    }
}
