<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {

        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();

        $this->post($project->path() . '/tasks', ['body' => 'Project Task']);
        $this->get($project->path())
             ->assertSee('Project Task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {   
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();
     
        $this->patch($project->tasks->first()->path(), $attributes = [
            'body' => 'changed',
        ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }
    /** @test */
    public function a_task_can_be_completed()
    {   
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();
     
        $this->patch($project->tasks->first()->path(), $attributes = [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }
    /** @test */
    public function a_task_can_be_marked_as_incompleted()
    {   
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();
     
        $this->patch($project->tasks->first()->path(),[
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->patch($project->tasks->first()->path(), $attributes = [
            'body' => 'changed',
            'completed' => false,
        ]);


        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->create();

        $task = factory('App\Task')->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $task)
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {   
        $this->signIn();
        $project = ProjectFactory::withTasks(1)
        ->create();

        $this->patch($project->tasks[0]->path(),  $attributes = ['body' => 'changed'])
             ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();
        $project = ProjectFactory::create();
        $this->post($project->path() . '/tasks', $attributes = ['body' => 'Project Task'])
             ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }
}
