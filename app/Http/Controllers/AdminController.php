<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Role;
use App\Student;
use App\Review;
use App\Teacher;
use App\UserSocialAccount;
use App\Mail\CourseApproved;
use App\Mail\CourseRejected;
use App\VueTables\EloquentVueTables;
use App\Rules\StrengthPassword;
use DB;
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
        //Una manera de fer-ho
            // $students = User::where('role_id', '=', Role::STUDENT)
            // ->paginate(10);
        //Una altra manera amb dades de dues taules
        $students = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->select('users.id as id', 'students.id as student_id', 'users.name as name',
            'users.last_name as last_name','students.title as title', 'users.email as email',
            'users.created_at as created_at')
            ->paginate(10);
    //retorna la vista home amb la variable estudiants
		return view('admin.students',compact('students'));
    }


    public function studentsDestroy ($id) {
        try {
            //Borramos el estudiante
            $student = Student::where('user_id', '=', $id);
            $student->delete();

            //Finalmente borramos el usuario
            //Accedemos al usuario
            $user = User::find($id);

            //Borramos el accesso Social
            $user->socialAccount()->delete();

            //Borramos las reviews
            $user->reviews()->delete();

            //Borramos el usuario
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
        $teachers = DB::table('users')
            ->join('teachers', 'users.id', '=', 'teachers.user_id')
            ->select('users.id as id', 'teachers.id as teacher_id', 'users.name as name',
            'users.last_name as last_name','teachers.title as title', 'users.email as email',
            'users.created_at as created_at')
            ->paginate(10);
    //retorna la vista admin.teachers amb la variable teachers
		return view('admin.teachers',['teachers' => $teachers]);
    }

    public function teachersDestroy ($id) {
        try {
            $teacher= Teacher::where('user_id', '=', $id)->first();
            // dd($teacher->id);
            //Borramos los cursos
            $teacher->courses()->delete();
            //Borramos el profesor
            $teacher->delete();
            //No esborrem l'usuari pero lo canviem
            //el rol a estudiant
            $user= User::where('id', '=', $id)->first();
            $user->role_id = Role::STUDENT;
            $user->save();
			return back()->with('message', ['success', __("Usuario profesor eliminado correctamente")]);
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error eliminando el profesor")]);
		}
    }
}

