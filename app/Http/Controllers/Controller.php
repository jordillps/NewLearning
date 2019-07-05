<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setLanguage ($language) {
    	if(array_key_exists( $language, config('languages'))) {
			//applocale es nom de la sessio
			//que ens determinara el llenguatge escollit
    		session()->put('applocale', $language);
	    }
	    return back();
    }
}
