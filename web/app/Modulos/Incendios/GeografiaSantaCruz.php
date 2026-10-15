<?php

namespace App\Modulos\Incendios;

class GeografiaSantaCruz
{
    private array $limite;

    public function __construct()
    {
        $this->limite = json_decode(file_get_contents(config('incendios.archivo_limite')), true, 512, JSON_THROW_ON_ERROR);
    }

    public function obtenerLimite(): array
    {
        return $this->limite;
    }

    /** NASA espera oeste, sur, este y norte. La caja sirve para consultar; el polígono para filtrar. */
    public function obtenerCajaConsulta(): string
    {
        $coordenadas = $this->limite['geometry']['type'] === 'Polygon'
            ? $this->limite['geometry']['coordinates'][0]
            : array_merge(...array_map(fn ($poligono) => $poligono[0], $this->limite['geometry']['coordinates']));

        $longitudes = array_column($coordenadas, 0);
        $latitudes = array_column($coordenadas, 1);

        return implode(',', [min($longitudes), min($latitudes), max($longitudes), max($latitudes)]);
    }

    public function contieneCoordenada(float $latitud, float $longitud): bool
    {
        $poligonos = $this->limite['geometry']['type'] === 'Polygon'
            ? [$this->limite['geometry']['coordinates']]
            : $this->limite['geometry']['coordinates'];

        foreach ($poligonos as $poligono) {
            if (! $this->puntoDentroDelAnillo($longitud, $latitud, $poligono[0])) {
                continue;
            }

            // Los anillos interiores son huecos y no pertenecen al departamento.
            foreach (array_slice($poligono, 1) as $hueco) {
                if ($this->puntoDentroDelAnillo($longitud, $latitud, $hueco)) {
                    continue 2;
                }
            }

            return true;
        }

        return false;
    }

    private function puntoDentroDelAnillo(float $longitud, float $latitud, array $anillo): bool
    {
        $estaDentro = false;
        $cantidad = count($anillo);

        // Un rayo horizontal cambia entre dentro/fuera cada vez que cruza un borde.
        for ($indice = 0, $anterior = $cantidad - 1; $indice < $cantidad; $anterior = $indice++) {
            [$longitudActual, $latitudActual] = $anillo[$indice];
            [$longitudAnterior, $latitudAnterior] = $anillo[$anterior];

            if (($latitudActual > $latitud) !== ($latitudAnterior > $latitud)) {
                $longitudCruce = ($longitudAnterior - $longitudActual) * ($latitud - $latitudActual)
                    / ($latitudAnterior - $latitudActual) + $longitudActual;
                if ($longitud < $longitudCruce) {
                    $estaDentro = ! $estaDentro;
                }
            }
        }

        return $estaDentro;
    }
}
