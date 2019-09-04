<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property int owner_id
 * @property User owner
 * @property Collection tasks
 * @property string title
 * @property string description
 * @property Collection activity
 */
class Project extends Model
{
    protected $guarded = [];

    /**
     * @return string
     */
    public function path(): string
    {
        return "/projects/{$this->id}";
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param string $body
     * @return Task|Model
     */
    public function addTask(string $body): Task
    {
        return $this->tasks()->create(compact('body'));
    }

    /**
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return HasMany
     */
    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }

    /**
     * @param string $description
     */
    public function recordActivity(string $description)
    {
        $this->activity()->create(compact('description'));
    }

}
