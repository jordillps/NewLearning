@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar estudiantes', 'icon' => 'unlock-alt'])
@endsection

@section('content')
<div class="pl-5 pr-5">
    <div class="row justify-content-center">
        <div class="table-responsive-md">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de alta</th>
                        <th colspan="2">Administración</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <th scope="row">{{ $student->id }}</th>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->created_at->format('d/m/Y') }}</td>
                        <td >
                            <form action="{{ route('admin.studentsedit', ['id' => $student->id]) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.studentsdestroy', ['id' => $student->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8">{{ __("No hay ningún estudiante") }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="row justify-content-left">
                {{ $students->links()}}
            </div>
        </div>

    </div>
</div>
@endsection
