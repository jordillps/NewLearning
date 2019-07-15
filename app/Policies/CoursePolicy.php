<?php

namespace App\Policies;

use App\User;
use App\Course;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    //Ens dira si un usuari pot optar a un curs
    public function opt_for_course (User $user, Course $course) {
        //si l'usuari no es profeessor o no es el qui ha impartit el curs
        // l'usuari podrà accedir al curs
        return ! $user->teacher || $user->teacher->id !== $course->teacher_id;
        
    }

    // Per saber si l'usuari es pot subscriure a algun plan
    public function subscribe (User $user) {
        // L'usuari no es administrador i no està subscrit anteriorment
        // return $user->role_id !== Role::ADMIN && ! $user->subscribed('main');
        return true;
    }

    // Per a saber si l'estudiant es pot subscriure
    public function inscribe (User $user, Course $course) {
        // La relacio studiant-cursos conte a aquest estudiant
    	return ! $course->students->contains($user->student->id);
    }

	public function review (User $user, Course $course) {
		return ! $course->reviews->contains('user_id', $user->id);
    }
    
    //Metodes creats per defecte que no necessitem
    
    /**
     * Determine whether the user can view any courses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    // public function viewAny(User $user)
    // {
        
    // }

    /**
     * Determine whether the user can view the course.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return mixed
     */
    // public function view(User $user, Course $course)
    // {
        
    // }

    /**
     * Determine whether the user can create courses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    // public function create(User $user)
    // {
    //     
    // }

    /**
     * Determine whether the user can update the course.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return mixed
     */
    // public function update(User $user, Course $course)
    // {
        
    // }

    /**
     * Determine whether the user can delete the course.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return mixed
     */
    // public function delete(User $user, Course $course)
    // {
        
    // }

    /**
     * Determine whether the user can restore the course.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return mixed
     */
    // public function restore(User $user, Course $course)
    // {
        
    // }

    /**
     * Determine whether the user can permanently delete the course.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return mixed
     */
    // public function forceDelete(User $user, Course $course)
    // {
        
    // }
}
