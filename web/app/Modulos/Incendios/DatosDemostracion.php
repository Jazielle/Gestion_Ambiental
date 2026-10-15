<?php

namespace App\Modulos\Incendios;

use Carbon\CarbonImmutable;

class DatosDemostracion
{
    /** Detecciones inventadas para aprender y probar. No provienen de NASA. */
    public function obtenerFocos(CarbonImmutable $fechaReferencia): array
    {
        // Categorías: ubicación [latitud, longitud], antigüedad en horas, confianza y potencia (MW).
        $escenarios = [
            [-16.35, -61.10, 1, 'alta', 36.2],
            [-16.63, -60.72, 3, 'nominal', 14.8],
            [-17.06, -61.47, 5, 'alta', 42.6],
            [-17.45, -60.74, 8, 'baja', 7.4],
            [-15.88, -62.31, 12, 'nominal', 18.5],
            [-17.60, -62.05, 18, 'alta', 29.1],
            [-16.04, -60.21, 22, 'nominal', 11.2],
            [-18.25, -59.78, 28, 'alta', 51.0],
            [-16.80, -62.73, 35, 'baja', 4.7],
            [-17.18, -63.04, 46, 'nominal', 20.3],
            [-18.00, -61.20, 54, 'alta', 31.7],
            [-15.46, -61.92, 68, 'nominal', 16.0],
            [-18.68, -60.63, 82, 'baja', 6.8],
            [-16.95, -59.93, 96, 'alta', 46.9],
            [-17.90, -62.64, 118, 'nominal', 12.4],
            [-19.18, -61.46, 145, 'nominal', 9.5],
        ];

        return array_map(function (array $escenario, int $indice) use ($fechaReferencia): array {
            [$latitud, $longitud, $horas, $confianza, $potencia] = $escenario;

            return [
                'identificador' => 'demo-'.str_pad((string) ($indice + 1), 3, '0', STR_PAD_LEFT),
                'latitud' => $latitud,
                'longitud' => $longitud,
                'fechaDeteccion' => $fechaReferencia->subHours($horas)->toIso8601String(),
                'satelite' => 'Suomi NPP (simulado)',
                'instrumento' => 'VIIRS',
                'confianza' => $confianza,
                'potenciaRadiativa' => $potencia,
            ];
        }, $escenarios, array_keys($escenarios));
    }
}
