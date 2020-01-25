<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $user = $this->signIn();
        $this->get('/projects/create')->assertStatus(200);

        $project = factory(Project::class)->make(['owner_id' => $user->id]);

        $this->post('/projects', $project->toArray());
        $this->assertDatabaseHas('projects', $project->toArray());
        $this->get($project->path())
             ->assertSee($project->title);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->create();
        $this->delete($project->path())->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function an_authenticated_cannot_delete_project()
    {
        $user = $this->signIn();
        $project = ProjectFactory::create();

        $this->delete($project->path())->assertForbidden();

        $project->invite($user);

        $this->delete($project->path())->assertForbidden();
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());
        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function a_user_can_update_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->create();

        $this->patch($project->path(), $attributes = [
            'notes' => 'changed',
            'description' => 'changed',
            'title' => 'changed',
        ])->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_general_notes()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->create();

        $this->patch($project->path(), $attributes = [
            'notes' => 'changed',
        ]);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();
        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->delete($project->path())->assertRedirect('/login');
        $this->get('/projects/{project}/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertForbidden();
    }

    /** @test */
    public function an_authenticated_user_cannot_update_projects_of_others()
    {
        $this->signIn();
        $project = factory('App\Project')->create();

        $this->patch($project->path(), $project->toArray())->assertForbidden();
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
