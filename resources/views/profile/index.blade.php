@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Configura tu perfil', 'icon' => 'user-circle'])
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __("Actualiza tus datos") }}
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->role_id ==1)
                            <form method="POST" action="{{ route('admin.updateByAdmin',['id'=>$user->id]) }}" novalidate>
                        @else
                            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @endif
                        {{-- <form method="POST" action="{{ route('profile.update') }}" novalidate> --}}
                            @csrf
                            {{-- Necessitem que el metode del formulari sigui PUT --}}
                            {{-- perque a la ruta de web.php utilitzem el verb html PUT --}}
                            @method('PUT')

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Correo electrónico") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        readonly
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email"
                                        value="{{ old('email') ?: $user->email }}"
                                        required
                                        autofocus
                                    />

                                    @if($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="password"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __("Contraseña") }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password"
                                        required
                                    />

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __("Confirma la contraseña") }}
                                </label>

                                <div class="col-md-6">
                                    <input
                                        id="password-confirm"
                                        type="password"
                                        class="form-control"
                                        name="password_confirmation"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __("Actualizar datos") }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if( ! $user->teacher)
                {{-- Usuari que no es un professor --}}
                    <div class="card">
                        <div class="card-header">
                            {{ __("Convertirme en profesor de la plataforma") }}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('solicitude.teacher') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fa fa-graduation-cap"></i> {{ __("Solicitar") }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                {{-- Quan l'estudiant es profesor --}}
                    <div class="card">
                        <div class="card-header">
                            {{ __("Administrar los cursos que imparto") }}
                        </div>
                        <div class="card-body">
                            <a href="{{ route('teacher.courses') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-leanpub"></i> {{ __("Administrar ahora") }}
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            {{ __("Mis estudiantes") }}
                        </div>
                        <div class="card-body">
                            <table
                                class="table table-striped table-bordered nowrap"
                                cellspacing="0"
                                {{-- la id es per treballar amb datatables --}}
                                id="students-table"
                            >
                                <thead>
                                    <tr>
                                        <th>{{ __("ID") }}</th>
                                        <th>{{ __("Nombre") }}</th>
                                        <th>{{ __("Email") }}</th>
                                        <th>{{ __("Cursos") }}</th>
                                        <th>{{ __("Acciones") }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif

                @if($user->socialAccount)
                    <div class="card">
                        <div class="card-header">
                            {{ __("Acceso con Socialite") }}
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-dark btn-block">
                                {{ __("Registrado con") }}: <i class="fa fa-{{ $user->socialAccount->provider }}"></i>
                                {{ $user->socialAccount->provider }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('partials.modal')
@endsection

@push('scripts')
    {{-- cdn DataTables --}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script>
        let dt;//datatables
        let modal = jQuery("#appModal");
        jQuery(document).ready(function() {
            dt = jQuery("#students-table").DataTable({
                pageLength: 5,
                lengthMenu: [ 5, 10, 25, 50, 75, 100 ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('teacher.students') }}',
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                columns: [
                    {data: 'user.id', visible: false},
                    {data: 'user.name'},
                    {data: 'user.email'},
                    {data: 'courses_formatted'},
                    {data: 'actions'}
                ]
            });
            //btnEmail prové de l'arxiu actions.blade.php
            jQuery(document).on("click", '.btnEmail', function (e) {
                e.preventDefault();
                //data(id)  prové de l'arxiu actions.blade.php data-id
                const id = jQuery(this).data('id');
                //modal-title,modalAction de l'arxiu modal.blade.php
                modal.find('.modal-title').text('{{ __("Enviar mensaje") }}');
                //Per mostrar el botó de "enviar mensaje"
                modal.find('#modalAction').text('{{ __("Enviar mensaje") }}').show();
                let $form = $("<form id='studentMessage'></form>");
                $form.append(`<input type="hidden" name="user_id" value="${id}" />`);
                $form.append(`<textarea class="form-control" name="message"></textarea>`);
                //colocara la variable form a modal-body de l'arxiu modal.blade.php
                modal.find('.modal-body').html($form);
                modal.modal();
            });

            //Enviament d'un mail a l'estudiant
            jQuery(document).on("click", "#modalAction", function (e) {
                jQuery.ajax({
                    url: '{{ route('teacher.send_message_to_student') }}',
                    type: 'POST',
                    //Relacionat amb el token de l'arxiu app.blade.php
                    headers: {
                        'x-csrf-token': $("meta[name=csrf-token]").attr('content')
                    },
                    //les dades que enviem a través de ajax
                    //studentMessage es la id del formulari(variable $form anterior)
                    //serialize es per enviar la informacio
                    data: {
                        //Convertim la informacio del formulari a una cadena
                        info: $("#studentMessage").serialize()
                    },
                    success: (res) => {
                        if(res.res) {
                            //ocultem el boto d'envviar
                            modal.find('#modalAction').hide();
                            //missatge d'exit
                            modal.find('.modal-body').html('<div class="alert alert-success">{{ __("Mensaje enviado correctamente") }}</div>');
                        } else {
                            //missatge d'error
                            modal.find('.modal-body').html('<div class="alert alert-danger">{{ __("Ha ocurrido un error enviando el correo") }}</div>');
                        }
                    }
                })
            })
        })
    </script>
@endpush
