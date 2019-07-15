<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Hem de definir el constructor perque incorporarem un middleware
    //per a traduir l'aplicació
	public function __construct() {
		$this->middleware(function($request, $next) {
            //main es el nom del conjunt de plans
            // o sigui que el if ens dirà si esta subscrit a algun plan
			if ( auth()->user()->subscribed('main') ) {
				return redirect('/')
					->with('message', ['warning', __("Actualmente ya estás suscrito a otro plan")]);
			}
			return $next($request);
        })
        //amb only podem filtrar els metodes als que volem que s'apliqui
        //el middleware
		->only(['plans', 'processSubscription']);
	}

	public function plans () {
		return view('subscriptions.plans');
    }

    public function processSubscription () {
	    $token = request('stripeToken');
	    try {
			//Si es posa informacio en el formulari coupon
			if ( \request()->has('coupon')) {
				\request()->user()->newSubscription('main', \request('type'))
					->withCoupon(\request('coupon'))->create($token);
			//Si no es posa informació en el formulari coupon
			} else {
				\request()->user()->newSubscription('main', \request('type'))
				          ->create($token);
			}
		    return redirect(route('subscriptions.admin'))
			    ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
	    } catch (\Exception $exception) {
			$error = $exception->getMessage();
			//Si existeix error retornem a la vista anterior:back
			//Amb with passem el parametre message
			//Passem la variable error
	    	return back()->with('message', ['danger', $error]);
	    }
    }

    public function admin () {
		$subscriptions = auth()->user()->subscriptions;
		return view('subscriptions.admin', compact('subscriptions'));
    }

    public function resume () {
		$subscription = \request()->user()->subscription(\request('plan'));
		if ($subscription->cancelled() && $subscription->onGracePeriod()) {
			//Resume() torna a reanudar la suscrripcio
			\request()->user()->subscription(\request('plan'))->resume();
			return back()->with('message', ['success', __("Has reanudado tu suscripción correctamente")]);
		}
		return back();
    }

    public function cancel () {
		auth()->user()->subscription(\request('plan'))->cancel();
	    return back()->with('message', ['success', __("La suscripción se ha cancelado correctamente")]);
    }
}

