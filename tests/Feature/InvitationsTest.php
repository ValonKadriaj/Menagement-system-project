<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_owner_can_invite_a_user()
    {   
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $userToInvite = factory(User::class)->create();

        $this->post($project->path() . '/invitations', [
            'email' => $userToInvite->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_valid_birboard_account()
    {   
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->post($project->path() . '/invitations', [
            'email' => 'notuser@example.com',
        ])->assertSessionHasErrors([
            'email' => 'the user you are invating most have valid birboard account',
        ], null, 'invitations');
    }

    /** @test */
    public function non_owners_may_not_invite_user()
    {   
        $project = ProjectFactory::create();

        $user = factory(User::class)->create();

        $this->signIn($user);
        $this->post($project->path().'/invitations')->assertForbidden();

        $project->invite($user);

        $this->post($project->path().'/invitations')->assertForbidden();

    }

    /** @test */
    public function invited_user_may_update_project_details()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = factory(User::class)->create());
        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'new user']);
        $this->assertDatabaseHas('tasks', $task);
    }
}
