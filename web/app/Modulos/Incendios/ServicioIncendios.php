<?php

namespace App\Modulos\Incendios;

use Carbon\CarbonImmutable;

class ServicioIncendios
{
    public function __construct(
        private DatosDemostracion $demostracion,
        private ServicioNasaFirms $nasa,
        private GeografiaSantaCruz $geografia,
    ) {}

    public function consultar(int $dias, string $fuente): array
    {
        $fechaConsulta = CarbonImmutable::now('UTC');
        $fechaInicio = $fechaConsulta->subDays($dias);
        $focos = $fuente === 'nasa'
            ? $this->nasa->consultar($dias, $fechaConsulta)
            : $this->demostracion->obtenerFocos($fechaConsulta);

        $focosFiltrados = [];
        foreach ($focos as $foco) {
            $fechaDeteccion = CarbonImmutable::parse($foco['fechaDeteccion']);
            if ($fechaDeteccion->betweenIncluded($fechaInicio, $fechaConsulta)
                && $this->geografia->contieneCoordenada($foco['latitud'], $foco['longitud'])) {
                $focosFiltrados[$foco['identificador']] = $foco;
            }
        }

        $focosFiltrados = array_values($focosFiltrados);
        usort($focosFiltrados, fn ($primero, $segundo) => strcmp($segundo['fechaDeteccion'], $primero['fechaDeteccion']));

        return [
            'focos' => $focosFiltrados,
            'resumen' => [
                'total' => count($focosFiltrados),
                'confianzaAlta' => count(array_filter($focosFiltrados, fn ($foco) => $foco['confianza'] === 'alta')),
                'potenciaMaxima' => $focosFiltrados ? max(array_column($focosFiltrados, 'potenciaRadiativa')) : null,
            ],
            'consulta' => [
                'fuente' => $fuente,
                'simulados' => $fuente === 'demo',
                'dias' => $dias,
                'fechaConsulta' => $fechaConsulta->toIso8601String(),
                'fechaInicio' => $fechaInicio->toIso8601String(),
                'descripcion' => $fuente === 'demo' ? 'Datos simulados para demostración' : 'NASA FIRMS · VIIRS Suomi NPP',
            ],
        ];
    }
}
