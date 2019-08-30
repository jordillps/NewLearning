<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        //amb except ense estar autenticats podem accedir a la home de l'aplicació
    //     $this->middleware('auth')->except(['index']);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Definim les dades que volem mostrar
        //withcount es el nombre d'estudiants  que hi ha en el curs
    	$courses = Course::withCount(['students'])
            ->with('categoory', 'teacher', 'reviews')
            //clausula where
            ->where('status', Course::PUBLISHED)
            //ordenats segons data de publicacio
            ->latest()
            //laravel per defecte pagina a 15
            ->paginate(12);

            //per veure la informacio per pantalla
            //dd($courses);

        //retorna la vista home amb la variable courses
        return view('home', compact('courses'));
    }
}
