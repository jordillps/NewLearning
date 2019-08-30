<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @mixin \Eloquent
 */
class Course extends Model
{
    //Relacionat amb withTrashed() de TeacherController
    use SoftDeletes;

    const PUBLISHED = 1;
	const PENDING = 2;
	const REJECTED = 3;

	protected $withCount = ['reviews', 'students'];


	//Per mostrar les imatges
	public function pathAttachment () {
		return "/images/courses/" . $this->picture;
	}

	//Aquesta funcio defineix quina sera la clau
	//per a definir les routes
	//en aquest cas slug, route amigable de seo
	public function getRouteKeyName() {
		return 'slug';
	}

    //funcions que retornen dades dels cursos

    //Exemple de relacio courses *------1 categoria
    public function categoory () {
       	return $this->belongsTo(Categoory::class)->select('id', 'name');
	}

    //Exemple de relacio course 1------* goals
	public function goals () {
		return $this->hasMany(Goal::class)->select('id', 'course_id', 'goal');
	}

	public function level () {
		return $this->belongsTo(Level::class)->select('id', 'name');
	}

	public function reviews () {
		return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
	}

	public function requirements () {
		return $this->hasMany(Requirement::class)->select('id', 'course_id', 'requirement');
	}

    //Exemple de relacio course *-----* student
    //cal fer la relació a la classe student
	public function students () {
		return $this->belongsToMany(Student::class);
	}

    //Exemple de relacio course *-----1 teacher
	public function teacher () {
		return $this->belongsTo(Teacher::class);
	}

	//Per obtenir el rating que son la mitja de les reviews
	public function getCustomRatingAttribute () {
		return $this->reviews->avg('rating');
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
	 */
	 public function relatedCourses () {
		return Course::with('reviews')->whereCategooryId($this->categoory->id)
			->where('id', '!=', $this->id)
			->latest()
			->limit(6)
			->get();
	}


}
