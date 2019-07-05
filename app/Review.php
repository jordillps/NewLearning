<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Review
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review query()
 * @mixin \Eloquent
 */
class Review extends Model
{
    //
    protected $fillable = ['course_id', 'user_id', 'rating', 'comment'];

    public function course () {
    	return $this->belongsTo(Course::class);
    }

    public function user () {
      return $this->belongsTo(User::class);
    }
}
