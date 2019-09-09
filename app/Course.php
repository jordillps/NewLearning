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

    //Per permetre omplir les dades dels nous cursos
    protected $fillable = ['teacher_id', 'name', 'description', 'picture', 'level_id', 'categoory_id', 'status'];

    const PUBLISHED = 1;
	const PENDING = 2;
	const REJECTED = 3;

    protected $withCount = ['reviews', 'students'];

    //Per guardar les metes i els requeriments quan
    //estem creant un curs
    public static function boot () {
		parent::boot();

		static::saving(function(Course $course) {
			if( ! \App::runningInConsole() ) {
				$course->slug = str_slug($course->name, "-");
			}
		});

        //S'executarà quan guardem el curs, tant si l'hem creat com
        //si l'hem actualitzat
        //Per únicament crear seria static::created
        //Per únicament actulaitzar seria::updated
		static::saved(function (Course $course) {
			if ( ! \App::runningInConsole()) {
				if ( request('requirements')) {
					foreach (request('requirements') as $key => $requirement_input) {
						if ($requirement_input) {
							Requirement::updateOrCreate(['id' => request('requirement_id'. $key)], [
								'course_id' => $course->id,
								'requirement' => $requirement_input
							]);
						}
					}
				}

				if(request('goals')) {
					foreach(request('goals') as $key => $goal_input) {
						if( $goal_input) {
							Goal::updateOrCreate(['id' => request('goal_id'.$key)], [
								'course_id' => $course->id,
								'goal' => $goal_input
							]);
						}
					}
				}
			}
		});
	}


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
