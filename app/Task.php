<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    /**
     * @param string $description
     */
    public function recordActivity(string $description)
    {
        $this->activity()->create([
            'description' => $description,
            'project_id' => $this->project_id,
        ]);
    }

    /**
     * @return MorphMany
     */
    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
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
