<li><a class="nav-link" href="#">{{ __("Mi perfil") }}</a></li>
<li><a class="nav-link" href="{{ route('invoices.admin')}}">{{ __("Mis facturas") }}</a></li>
<li><a class="nav-link" href="#">{{ __("Mis cursos") }}</a></li>
<li><a class="nav-link" href="{{ route('subscriptions.admin')}}">{{ __("Mis suscripciones") }}</a></li>
@include('partials.navigations.logged')