<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;

/**
 * App\User
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $picture
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $last_name
 * @property string $slug
 * @property-read \App\Role $role
 * @property-read \App\UserSocialAccount $socialAccount
 * @property-read \App\Student $student
 * @property-read \App\Teacher $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSlug($value)
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable;

    //Per utilitzar l'event creating
    //incorporar el valor de slug en la creació de l'usuari
    protected static function boot () {
		parent::boot();
		static::creating(function (User $user) {
            //Si no s'està executant aquesta petició des de la terminal, o sigui amb
            // php artisan
			if( ! \App::runningInConsole()) {
				$user->slug = str_slug($user->name . " " . $user->last_name, "-");
			}
		});
	}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pathAttachment () {
    	return "/images/users/" . $this->picture;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function navigation () {
        //comprovem si  esta autenticat
        //si ho esta recollim el nom
        //si no ho esta el definim com a 'guest'
    	return auth()->check() ? auth()->user()->role->name : 'guest';
    }


    public function role () {
    	return $this->belongsTo(Role::class);
    }

    public function student () {
    	return $this->hasOne(Student::class);
    }

    public function teacher () {
    	return $this->hasOne(Teacher::class);
    }

    public function socialAccount () {
    	return $this->hasOne(UserSocialAccount::class);
    }

    public function reviews () {
    	return $this->hasMany(Review::class);
    }
}
