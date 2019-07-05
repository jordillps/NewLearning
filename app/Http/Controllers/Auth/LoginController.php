<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use App\UserSocialAccount;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Reescribim el metode logout per tancar sessio
    public function logout (Request $request) {
        auth()->logout();
        //esborra les sessions
    	session()->flush();
    	return redirect('/login');
    }

    //passem el parametre de driver que ens dirà si es fb o github
    public function redirectToProvider (string $driver) {
        
    	return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback (string $driver) {
        //Quan abans d'entrar a l'aplicació utilitzant facebook o github
        //fem clic al botó de cancelar
        //github no te boto de cancelar
        if( ! request()->has('code') || request()->has('denied')) {
    		session()->flash('message', ['danger', __("Inicio de sesión cancelado")]);
    		return redirect('login');
	    }
        $socialUser = Socialite::driver($driver)->user();
        //imprimir per pantalla el resultat
        //dd($socialUser);

        $user = null;
    	$success = true;
        $email = $socialUser->email;
        //Metodes definits per eloquent(whereEmail)
        //Cerquem un usuari amb el email i ens quedem
        //amb el primer registre
        //check es refereix a si existeix l'usuari
    	$check = User::whereEmail($email)->first();
    	if($check) {
    		$user = $check;
	    } else {
            //iniciem transaccio per donar d'alta l'usuari
    		\DB::beginTransaction();
    		try {
    			$user = User::create([
    				"name" => $socialUser->name,
				    "email" => $email
			    ]);
    			UserSocialAccount::create([
    				"user_id" => $user->id,
				    "provider" => $driver,
				    "provider_uid" => $socialUser->id
			    ]);
    			Student::create([
    				"user_id" => $user->id
			    ]);
		    } catch (\Exception $exception) {
                //si alguna paart de la transaccio ha fallat
                //fem el rollback de la transaccio
				$success = $exception->getMessage();
				\DB::rollBack();
		    }
	    }

        //fem el commit de la transaccio
	    if($success === true) {
    		\DB::commit();
    		auth()->loginUsingId($user->id);
    		return redirect(route('home'));
        }
        //no cal posar else perque el if ja te un return
        //O sigui l'execucio arribara aqui nomes si succes es falsa
        //es a dir la transaccio no s'ha executat
        //succees en aquest cas es l'error
	    session()->flash('message', ['danger', $success]);
    	return redirect('login');
    }
}
