<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Categoory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Categoory query()
 * @mixin \Eloquent
 */
class Categoory extends Model
{
    //Get the courses by category
    public function courses () {
    	return $this->hasMany(Course::class);
    }
}
