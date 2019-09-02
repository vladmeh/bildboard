<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Project project
 * @property int id
 */
class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($task) {
            $task->project->recordActivity('created_task');
        });

    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->project->recordActivity('completed_task');
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }
}
