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
    use RecordActivity;

    protected static $recordableEvents = ['created', 'deleted'];
    protected $guarded = [];
    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean',
    ];

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incomplete_task');
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
