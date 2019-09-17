<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Role;
use App\Student;
use App\Review;
use App\UserSocialAccount;
use App\Mail\CourseApproved;
use App\Mail\CourseRejected;
use App\VueTables\EloquentVueTables;
use App\Rules\StrengthPassword;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function courses () {
		return view('admin.courses');
	}

	public function coursesJson () {
        //Comprovem si es una peticio ajax
		if(request()->ajax()) {
            $vueTables = new EloquentVueTables;
            //Relacionat amb VueTablesInteface
            //Model,columns,relations
			$data = $vueTables->get(new Course, ['id', 'name', 'status'], ['reviews']);
			return response()->json($data);
		}
		return abort(401);
	}

	public function updateCourseStatus () {
		if (\request()->ajax()) {
			$course = Course::find(\request('courseId'));

			if(
				(int) $course->status !== Course::PUBLISHED &&
				! $course->previous_approved &&
				\request('status') === Course::PUBLISHED
			) {
				$course->previous_approved = true;
				Mail::to($course->teacher->user)->send(new CourseApproved($course));
			}

			if(
				(int) $course->status !== Course::REJECTED &&
				! $course->previous_rejected &&
				\request('status') === Course::REJECTED
			) {
				$course->previous_rejected = true;
				Mail::to($course->teacher->user)->send(new CourseRejected($course));
			}

			$course->status = \request('status');
			$course->save();
			return response()->json(['msg' => 'ok']);
		}
		return abort(401);
	}

	public function students () {
        //Definim les dades que volem mostrar
        //withcount es el nombre d'estudiants  que hi ha en el curs
        $students = User::where('role_id', '=', Role::STUDENT)
        ->paginate(10);
    //retorna la vista home amb la variable estudiants
		return view('admin.students',compact('students'));
    }


    public function studentsDestroy ($id) {
        try {
            //Borramos el estudiante
            $student = Student::where('user_id', '=', $id);
            $student->delete();
            //Borramos el accesso Social
            $social_account = UserSocialAccount::where('user_id', '=', $id);
            $student->delete();
            //Borramos sus revisiones
            $review = Review::where('user_id', '=', $id);
            $review->delete();
            //Finalmente borramos el usuario
            $user = User::where('id', '=', $id);
            $user->delete();
			return back()->with('message', ['success', __("Usuario estudiante eliminado correctamente")]);
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error eliminando el estuditante")]);
		}
    }

    //Index student or teacher from Admin
    public function indexByAdmin ($id) {
        $user = User::where('id', '=', $id)->first();
    	return view('profile.index', compact('user'));
    }

    //Update student or teacher from Admin
    public function updateByAdmin ($id) {
        //Important afegir first() quan cerquem un element
        $user = User::where('id', '=', $id)->first();
		$this->validate(request(), [
			'password' => ['confirmed', new StrengthPassword]
		]);
		$user->password = bcrypt(request('password'));
        $user->save();
        return back()->with('message', ['success', __("Usuario actualizado correctamente")]);
    }

	public function teachers () {
        $teachers = User::where('role_id', '=', Role::TEACHER)
        ->paginate(10);
    //retorna la vista home amb la variable estudiants
		return view('admin.teachers',compact('teachers'));
    }

    public function teachersDestroy ($id) {
        try {
            //Borramos el profesor
            $teacher = Student::where('user_id', '=', $id);
            $teacher->delete();
            //Borramos el profesor
            $courses = Course::where('teacher_id', '=', $id);
            $courses->delete();
            //Borramos el accesso Social
            $social_account = UserSocialAccount::where('user_id', '=', $id);
            $social_account->delete();
            //Borramos sus revisiones
            $review = Review::where('user_id', '=', $id);
            $review->delete();
            //Finalmente borramos el usuario
            $user = User::where('id', '=', $id);
            $user->delete();
			return back()->with('message', ['success', __("Usuario estudiante eliminado correctamente")]);
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error eliminando el profesor")]);
		}
    }
}

