<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{   
    use RecordActivity;

    protected $guarded = [];
    public $touches = ['project'];
    protected static $recordableEvents = ['created', 'deleted'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function complete()
    {
        if ($this->completed === true) return;

        $this->update([
            'completed' => true,
        ]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        if ($this->completed === false) return;
        $this->update([
            'completed' => false,
        ]);
        $this->recordActivity('incompleted_task');
    }

    public function project()
    {
        return $this->BelongsTo(Project::class);
    }

    public function path()
    {
        return '/projects/' . $this->project->id . '/tasks/' . $this->id;
    }

   
}
