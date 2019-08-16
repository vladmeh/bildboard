<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 */
class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/projects/{$this->id}";
    }
}
