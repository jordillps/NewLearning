<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{
    //fillable ens indica quins son els camps de la taula que
    //omplirem amb la funcio create
    protected $fillable = ['user_id', 'provider', 'provider_uid'];

    //No crearà els timestamps quan ceem el social user
    // no els volem crear perque no els tenim a la taula
    public $timestamps = false;

    public function user () {
    	return $this->belongsTo(User::class);
    }
}
