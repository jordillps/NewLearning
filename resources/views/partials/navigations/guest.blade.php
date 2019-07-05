
@if($view_name!='auth-login')
 <li><a class="nav-link" href="{{ route('login') }}">{{ __("Iniciar sesión") }}</a></li>
@else
 <li><a class="nav-link" href="{{ route('register') }}">{{ __("Registrarme") }}</a></li>
@endif