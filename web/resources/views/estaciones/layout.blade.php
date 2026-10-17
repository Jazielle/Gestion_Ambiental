<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo') · GeoCruz</title>
    <link rel="icon" href="{{ asset('modulos/incendios/geocruz.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('modulos/estaciones/estaciones.css') }}">
</head>
<body>
    <header class="cabecera">
        <a class="marca" href="{{ route('incendios.inicio') }}">GeoCruz</a>
        <nav aria-label="Navegación principal">
            <a href="{{ route('incendios.pagina') }}">Focos de calor</a>
            <a href="{{ route('estaciones.index') }}" aria-current="page">Estaciones</a>
        </nav>
    </header>
    <main>
        <p class="contexto">ADMINISTRACIÓN / ESTACIONES DE MEDICIÓN</p>
        @if (session('estado'))
            <p class="aviso" role="status">{{ session('estado') }}</p>
        @endif
        @yield('contenido')
    </main>
</body>
</html>
