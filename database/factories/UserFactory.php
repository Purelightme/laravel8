<?php

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'phone' => $faker->phoneNumber,
        'password' => '$2y$10$NYiR9r.WXlmxB.YVbclhHeljX.N3Dd/QzP1Gtva4Gh0bWwWyGnjeq', // 123456
    ];
});
