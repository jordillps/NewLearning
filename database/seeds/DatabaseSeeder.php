<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('courses');
        Storage::deleteDirectory('users');

        Storage::makeDirectory('courses');
        Storage::makeDirectory('users');

        //En aquest cas no utilizem faker
        //Perque sabem els rols que volem
        factory(\App\Role::class, 1)->create(['name' => 'admin']);
        factory(\App\Role::class, 1)->create(['name' => 'teacher']);
        factory(\App\Role::class, 1)->create(['name' => 'student']);

        factory(\App\User::class, 1)->create([
        	'name' => 'Jordi',
	        'email' => 'jordi@gmail.com',
	        'password' => bcrypt('joibla068'),
	        'role_id' => \App\Role::ADMIN
        ])
        //Per seguir la condicio que tots els users son 
        //estudiants per defecte
        ->each(function (\App\User $u) {
        	factory(\App\Student::class, 1)->create(['user_id' => $u->id]);
        });

	    factory(\App\User::class, 1)->create([
		    'name' => 'student',
		    'email' => 'student@mail.com',
		    'password' => bcrypt('secret'),
		    'role_id' => \App\Role::STUDENT
	    ])
	        ->each(function (\App\User $u) {
	            factory(\App\Student::class, 1)->create(['user_id' => $u->id]);
	        });

	    factory(\App\User::class, 50)->create()
             ->each(function (\App\User $u) {
                 factory(\App\Student::class, 1)->create(['user_id' => $u->id]);
             });

	    factory(\App\User::class, 10)->create()
             ->each(function (\App\User $u) {
	             factory(\App\Student::class, 1)->create(['user_id' => $u->id]);
                 factory(\App\Teacher::class, 1)->create(['user_id' => $u->id]);
             });

	    factory(\App\Level::class, 1)->create(['name' => 'Beginner']);
	    factory(\App\Level::class, 1)->create(['name' => 'Intermediate']);
	    factory(\App\Level::class, 1)->create(['name' => 'Advanced']);
        factory(\App\Categoory::class, 5)->create();
        


	    factory(\App\Course::class, 50)
		    ->create()
		    ->each(function (\App\Course $c) {
		    	$c->goals()->saveMany(factory(\App\Goal::class, 2)->create());
                $c->requirements()->saveMany(factory(\App\Requirement::class, 4)->create());
                $c->reviews()->saveMany(factory(\App\Review::class, 2)->create());
		    });
    }
}
