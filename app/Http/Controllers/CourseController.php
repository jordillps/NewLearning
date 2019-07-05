<?php

namespace App\Http\Controllers;

use App\Course;
use App\Helpers\Helper;
use App\Http\Requests\CourseRequest;
use App\Mail\NewStudentInCourse;
use App\Review;

class CourseController extends Controller
{
    //
    public function show (Course $course) {
        //per mostrar el contingut del curs en la ruta
        //dd($couse);

        //Load es la ffuncio per a carregar la informacio
		$course->load([
            //$q es la variable del query builder
			'categoory' => function ($q) {
				$q->select('id', 'name');
			},
			'goals' => function ($q) {
				$q->select('id', 'course_id', 'goal');
			},
			'level' => function ($q) {
				$q->select('id', 'name');
			},
			'requirements' => function ($q) {
				$q->select('id', 'course_id', 'requirement');
            },
            'reviews' => function ($q) {
				$q->select('id', 'course_id', 'rating','user_id');
            },
            //o tambe podria ser
			//'reviews.user',
			'teacher'
        ])->get();

        //Per veure la informacio per pantalla
        //dd($course);

		$related = $course->relatedCourses();

		return view('courses.detail', compact('course', 'related'));
	}
}
