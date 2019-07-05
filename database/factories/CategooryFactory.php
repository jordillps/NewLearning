<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Categoory;
use Faker\Generator as Faker;

$factory->define(Categoory::class, function (Faker $faker) {
        return [
            'name' => $faker->randomElement(['PHP', 'JAVASCRIPT', 'JAVA', 'DISEÑO WEB', 'SERVIDORES', 'MYSQL', 'NOSQL', 'BIGDATA', 'AMAZON WEB SERVICES']),
            'description' => $faker->sentence
        ];
});
