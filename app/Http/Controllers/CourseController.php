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

        //Load es la funcio per a carregar la informacio
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
				$q->select('id', 'course_id', 'rating','user_id', 'comment', 'created_at');
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


	public function inscribe (Course $course) {
		//Accedim a la taula course_estudiants i insertem un registre
		$course->students()->attach(auth()->user()->student->id);

		\Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

		return back()->with('message', ['success', __("Inscrito correctamente al curso")]);
	}

	public function subscribed () {
		$courses = Course::whereHas('students', function($query) {
			$query->where('user_id', auth()->id());
		})->get();
		return view('courses.subscribed', compact('courses'));
	}

	public function addReview () {
		//Per veure tota la informació que enviem amb el formulari
		//dd(request()->all());
		Review::create([
			"user_id" => auth()->id(),
			//Variables que emviem des del formulari
			"course_id" => request('course_id'),
			"rating" => (int) request('rating_input'),
			"comment" => request('message')
		]);
		return back()->with('message', ['success', __('Muchas gracias por valorar el curso')]);
	}
}
