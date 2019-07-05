<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Student
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student query()
 * @mixin \Eloquent
 */
class Student extends Model
{
    //

    protected $fillable = ['user_id', 'title'];

    public function courses () {
    	return $this->belongsToMany(Course::class);
    }

    //Aquesta relació perque cada estudiant és un usuari
	public function user () {
		return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'email');
	}
}
