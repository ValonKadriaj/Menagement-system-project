<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function createing_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();
        $originalTitle = $project->title;
        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            $this->assertEquals('updated_project', $activity->description);
            $exepted = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'changed'],
            ];

            $this->assertEquals($exepted, $activity->changes);
        });
    }

    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some Task');

        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some Task', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);
        $project->refresh();
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false,
        ]);
        $project->refresh();
        $this->assertCount(4, $project->activity);

        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
        ->withTasks(1)
        ->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
