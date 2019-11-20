<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Chat;
use Faker\Generator as Faker;

$factory->define(Chat::class, function (Faker $faker) {
    return [
        'student_id' => $faker->numberBetween($min=1,$max=30),
        'mentor_id' => $faker->numberBetween($min=1,$max=30),
        'message' => $faker->text($maxNbChars = 150)
    ];
});
