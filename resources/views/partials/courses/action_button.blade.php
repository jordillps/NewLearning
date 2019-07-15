<div class="col-2">
    {{-- Per a saber si l'usuari està autenticat --}}
    @auth
    {{-- identificado --}}
        @can('opt_for_course', $course)
             @can('subscribe', \App\Course::class)
             {{-- Es pot subscriure perque encara no te cap plan contractat --}}
                <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('subscriptions.plans') }}">
                    <i class="fa fa-bolt"></i> {{ __("Suscribirme") }}
                </a>
             @else
             {{-- L'usuari esta subsccrit a un pla i volem saber si es pot 
             inscriure al curs --}}
                 @can('inscribe', $course)
                    {{-- <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('courses.inscribe', ['slug' => $course->slug]) }}"> --}}
                    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                        <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                    </a>
                 @else
                    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                        <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                    </a>
                 @endcan
             @endcan
        @else
            <a class="btn btn-subscribe btn-bottom btn-block" href="#">
                <i class="fa fa-user"></i> {{ __("Soy autor") }}
            </a>
        @endcan
    {{-- No identificado --}}
    @else
        <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('login') }}">
            <i class="fa fa-user"></i> {{ __("Acceder") }}
        </a>
    @endauth
</div>