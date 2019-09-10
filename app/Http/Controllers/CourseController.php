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

    public function create () {
		$course = new Course;
		$btnText = __("Enviar curso para revisión");
		return view('courses.form', compact('course', 'btnText'));
    }

    //Utilitzem for request per validar el formulari abans d'enviar-lo
    public function store (CourseRequest $course_request) {
        //dd($course_request->all());
        //picture es el nom de l'arxiu, courses es el path de l'arxiu es a dir
        ///storage/app/public/courses
        $picture = Helper::uploadFile('picture', 'courses');
        //afegim una nova variable a l'array $course_request
        //la key dela nova variable es picture i el valor $picture(nomde l'arxiu)
		$course_request->merge(['picture' => $picture]);
		$course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
        $course_request->merge(['status' => Course::PENDING]);
        //tots els camps excepte el token
        Course::create($course_request->input());
		return back()->with('message', ['success', __('Curso enviado correctamente, recibirá un correo con cualquier información')]);
    }

    public function edit ($slug) {
		$course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
			->whereSlug($slug)->first();
		$btnText = __("Actualizar curso");
		return view('courses.form', compact('course', 'btnText'));
	}

	public function update (CourseRequest $course_request, Course $course) {
        //Comprovem si exissteix foto
		if($course_request->hasFile('picture')) {
            //Esborrem l'antiga foto i pujem la nova
			\Storage::delete('courses/' . $course->picture);
			$picture = Helper::uploadFile( "picture", 'courses');
			$course_request->merge(['picture' => $picture]);
		}
		$course->fill($course_request->input())->save();

        //return back()->with('message', ['success', __('Curso actualizado')]);
        return redirect()->route('teacher.courses')->with('message', ['success', __('Curso actualizado')]);

	}

	public function destroy (Course $course) {
		try {
			$course->delete();
			return back()->with('message', ['success', __("Curso eliminado correctamente")]);
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error eliminando el curso")]);
		}
	}
}
