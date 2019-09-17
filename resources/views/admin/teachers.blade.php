@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar profesores', 'icon' => 'unlock-alt'])
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
                    @forelse($teachers as $teacher)
                    <tr>
                        <th scope="row">{{ $teacher->id }}</th>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->last_name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->created_at->format('d/m/Y') }}</td>
                        <td >
                            <form action="{{ route('admin.teachersedit', ['id' => $teacher->id]) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.teachersdestroy', ['id' => $teacher->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8">{{ __("No hay ningún profesor") }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="row justify-content-left">
                {{ $teachers->links()}}
            </div>
        </div>

    </div>
</div>
@endsection
