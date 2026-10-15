<?php

namespace App\Modulos\Incendios;

use Carbon\CarbonImmutable;
use RuntimeException;

class NormalizadorFirms
{
    /** Conservamos los encabezados originales de NASA solo al leer el CSV externo. */
    public function convertirCsv(string $contenidoCsv): array
    {
        $contenidoCsv = preg_replace('/^\xEF\xBB\xBF/', '', trim($contenidoCsv));
        if ($contenidoCsv === '') {
            throw new RuntimeException('NASA FIRMS devolvió una respuesta vacía o inválida.');
        }

        $lineas = preg_split('/\r\n|\n|\r/', $contenidoCsv);
        $encabezados = str_getcsv(array_shift($lineas), ',', '"', '');
        $camposObligatorios = ['latitude', 'longitude', 'acq_date', 'acq_time', 'confidence'];

        if (array_diff($camposObligatorios, $encabezados)) {
            throw new RuntimeException('NASA FIRMS no devolvió un CSV válido. Comprueba la clave y la disponibilidad del servicio.');
        }

        $focos = [];
        foreach ($lineas as $linea) {
            if (trim($linea) === '') {
                continue;
            }
            $valores = str_getcsv($linea, ',', '"', '');
            if (count($valores) !== count($encabezados)) {
                continue;
            }
            $fila = array_combine($encabezados, $valores);
            $foco = $this->normalizarFila($fila);
            if ($foco !== null) {
                $focos[$foco['identificador']] = $foco;
            }
        }

        return array_values($focos);
    }

    private function normalizarFila(array $fila): ?array
    {
        if (! is_numeric($fila['latitude']) || ! is_numeric($fila['longitude'])) {
            return null;
        }

        $latitud = (float) $fila['latitude'];
        $longitud = (float) $fila['longitude'];
        $horaUtc = str_pad(trim($fila['acq_time']), 4, '0', STR_PAD_LEFT);
        $fechaUtc = $fila['acq_date'].' '.substr($horaUtc, 0, 2).':'.substr($horaUtc, 2, 2);

        if (abs($latitud) > 90 || abs($longitud) > 180
            || ! preg_match('/^\d{4}$/', $horaUtc)
            || ! CarbonImmutable::canBeCreatedFromFormat($fechaUtc, 'Y-m-d H:i')) {
            return null;
        }

        $fecha = CarbonImmutable::createFromFormat('!Y-m-d H:i', $fechaUtc, 'UTC');
        $confianza = ['l' => 'baja', 'n' => 'nominal', 'h' => 'alta'][strtolower($fila['confidence'])] ?? 'desconocida';

        return [
            'identificador' => 'firms-'.substr(hash('sha256', implode('|', [$latitud, $longitud, $fechaUtc, $fila['satellite'] ?? '', $fila['instrument'] ?? 'VIIRS'])), 0, 16),
            'latitud' => $latitud,
            'longitud' => $longitud,
            'fechaDeteccion' => $fecha->toIso8601String(),
            'satelite' => $fila['satellite'] ?? 'Suomi NPP',
            'instrumento' => $fila['instrument'] ?? 'VIIRS',
            'confianza' => $confianza,
            'potenciaRadiativa' => isset($fila['frp']) && is_numeric($fila['frp']) ? (float) $fila['frp'] : null,
        ];
    }
}
