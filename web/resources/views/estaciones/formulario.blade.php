@extends('estaciones.layout')
@section('titulo', $estacion->exists ? 'Editar estación' : 'Registrar estación')
@section('contenido')
    <a class="volver" href="{{ route('estaciones.index') }}">← Volver a estaciones</a>
    <h1>{{ $estacion->exists ? 'Editar estación' : 'Registrar estación' }}</h1>
    <p>Todos los campos son obligatorios. Introduce coordenadas en grados decimales.</p>
    @if ($errors->any())
        <div class="error resumen-errores" role="alert"><strong>Revisa los campos indicados.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form class="panel formulario" method="post" action="{{ $estacion->exists ? route('estaciones.update', $estacion) : route('estaciones.store') }}">
        @csrf
        @if ($estacion->exists) @method('PUT') @endif
        <label for="nombre">Nombre de la estación</label>
        <input id="nombre" name="nombre" type="text" maxlength="150" required value="{{ old('nombre', $estacion->nombre) }}" @error('nombre') aria-invalid="true" aria-describedby="error-nombre" @enderror>
        @error('nombre')<p class="error" id="error-nombre">{{ $message }}</p>@enderror
        <div class="coordenadas">
            <div><label for="latitud">Latitud</label><input id="latitud" name="latitud" type="number" step="any" min="-90" max="90" required placeholder="-17.7833" value="{{ old('latitud', $estacion->latitud) }}" @error('latitud') aria-invalid="true" aria-describedby="error-latitud" @enderror>
            @error('latitud')<p class="error" id="error-latitud">{{ $message }}</p>@enderror</div>
            <div><label for="longitud">Longitud</label><input id="longitud" name="longitud" type="number" step="any" min="-180" max="180" required placeholder="-63.1821" value="{{ old('longitud', $estacion->longitud) }}" @error('longitud') aria-invalid="true" aria-describedby="error-longitud" @enderror>
            @error('longitud')<p class="error" id="error-longitud">{{ $message }}</p>@enderror</div>
        </div>
        <div class="acciones"><button class="boton" type="submit">{{ $estacion->exists ? 'Guardar cambios' : 'Registrar estación' }}</button><a href="{{ route('estaciones.index') }}">Cancelar</a></div>
    </form>
@endsection
