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
    //fillable son els camps que designem que es podem omplir
    //quan creem una instancia del model
    //el contrari es $guarded
    protected $fillable = ['user_id', 'title'];

    //Relacionat amb courses_formatted de index.blade.php
    protected $appends = ['courses_formatted'];

    public function courses () {
    	return $this->belongsToMany(Course::class);
    }

    //Aquesta relació perque cada estudiant és un usuari
	public function user () {
		return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'email');
    }

    public function getCoursesFormattedAttribute () {
    	return $this->courses->pluck('name')->implode('<br />');
	}




}
