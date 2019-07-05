<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    //definim prèviament 2 variables
    $name = $faker->sentence;
	$status = $faker->randomElement([\App\Course::PUBLISHED, \App\Course::PENDING, \App\Course::REJECTED]);
    return [
        'teacher_id' => \App\Teacher::all()->random()->id,
	    'categoory_id' => \App\Categoory::all()->random()->id,
	    'level_id' => \App\Level::all()->random()->id,
	    'name' => $name,
	    'slug' => str_slug($name, '-'),
        'description' => $faker->paragraph,
        //Més informació a github faker
        'picture' => \Faker\Provider\Image::image(storage_path() . '/app/public/courses', 600, 350, 'business', false),
	    'status' => $status,
	    'previous_approved' => $status !== \App\Course::PUBLISHED ? false : true,
	    'previous_rejected' => $status === \App\Course::REJECTED ? true : false,
    ];
});
