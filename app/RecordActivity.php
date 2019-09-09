<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordActivity
{
    /**
     * @var array
     */
    public $oldAttributes = [];

    /**
     * Boot the trait
     */
    public static function bootRecordActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function (Model $model) use ($event) {


                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function (Model $model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    /**
     * @return array
     */
    public static function recordableEvents(): array
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated', 'deleted'];
    }

    /**
     * @param string $description
     */
    public function recordActivity(string $description): void
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }

    /**
     * @return MorphMany
     */
    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * @return array|null
     */
    protected function activityChanges(): ?array
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' => array_except($this->getChanges(), 'updated_at')
            ];
        }

        return null;
    }

    /**
     * @param string $description
     * @return string
     */
    protected function activityDescription(string $description): string
    {
        return "{$description}_" . strtolower(class_basename($this));
    }
}