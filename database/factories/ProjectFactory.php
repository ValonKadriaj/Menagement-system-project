<?php

use App\Project;
use App\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'notes' => $faker->sentence(4),
        'description' => $faker->sentence(1),
        'owner_id' =>factory(User::class), 
    ];
});
