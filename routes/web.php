<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//route per a seleccionar el llenguatge
//el controlador serà Controller
Route::get('/set_language/{lang}', 'Controller@setLanguage')->name( 'set_language');

//routes per el login amb fb o github
Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//per pujar les imatges dels cursos
Route::get('/images/{path}/{attachment}', function($path, $attachment) {
	$file = sprintf('storage/%s/%s', $path, $attachment);
	if(File::exists($file)) {
		return Image::make($file)->response();
	}
});

Route::group(['prefix' => 'courses'], function () {

	// Route::group(['middleware' => ['auth']], function() {
	// 	Route::get('/subscribed', 'CourseController@subscribed')->name('courses.subscribed');
	// 	Route::get('/{course}/inscribe', 'CourseController@inscribe')->name('courses.inscribe');
	// 	Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');

	// 	Route::group(['middleware' => [sprintf('role:%s', \App\Role::TEACHER)]], function () {
	// 		Route::resource('courses', 'CourseController');
	// 	});
	// });

	//Per a mostrar els detalls dels cursos
	//el metoode show es el que definim al controlador
	Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});
