@extends('estaciones.layout')
@section('titulo', 'Estaciones de medición')
@section('contenido')
    <div class="encabezado">
        <div><h1>Estaciones de medición</h1><p>Registra y actualiza la ubicación de las estaciones.</p></div>
        <a class="boton" href="{{ route('estaciones.create') }}">Registrar estación</a>
    </div>
    @if ($estaciones->isEmpty())
        <section class="panel vacio"><h2>No hay estaciones registradas</h2><p>Registra la primera estación con su nombre y coordenadas.</p></section>
    @else
        <div class="panel tabla">
            <table>
                <caption>{{ $estaciones->total() }} {{ $estaciones->total() === 1 ? 'estación registrada' : 'estaciones registradas' }} · Página {{ $estaciones->currentPage() }} de {{ $estaciones->lastPage() }}</caption>
                <thead><tr><th scope="col">Nombre</th><th scope="col">Latitud</th><th scope="col">Longitud</th><th scope="col">Acción</th></tr></thead>
                <tbody>
                    @foreach ($estaciones as $estacion)
                        <tr><th scope="row">{{ $estacion->nombre }}</th><td>{{ $estacion->latitud }}</td><td>{{ $estacion->longitud }}</td><td><a href="{{ route('estaciones.edit', $estacion) }}" aria-label="Editar {{ $estacion->nombre }}">Editar</a></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav class="paginacion" aria-label="Páginas de estaciones">
            @if ($estaciones->previousPageUrl())<a href="{{ $estaciones->previousPageUrl() }}">← Anterior</a>@endif
            @if ($estaciones->nextPageUrl())<a href="{{ $estaciones->nextPageUrl() }}">Siguiente →</a>@endif
        </nav>
    @endif
@endsection
