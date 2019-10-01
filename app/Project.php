<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property int owner_id
 * @property string title
 * @property string description
 * @property User owner
 * @property Collection activity
 * @property Collection tasks
 * @property void members
 */
class Project extends Model
{
    use RecordActivity;

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
     * @param array $tasks
     * @return Collection
     */
    public function addTasks(array $tasks)
    {
        return $this->tasks()->createMany($tasks);
    }

    /**
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
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
    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }

    /**
     * @param User $user
     */
    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
