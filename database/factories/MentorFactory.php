<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Mentor;
use Faker\Generator as Faker;

$factory->define(Mentor::class, function (Faker $faker) {
    return [
        'student_id' => $faker->numberBetween($min=1,$max=30),
        'mentor_id' => $faker->numberBetween($min=1,$max=30)
    ];
});
