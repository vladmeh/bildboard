<?php

/** @var Factory $factory */

use App\Task;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence
    ];
});
