<?php

namespace App\Modulos\Incendios;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ServicioNasaFirms
{
    public function __construct(
        private GeografiaSantaCruz $geografia,
        private NormalizadorFirms $normalizador,
    ) {}

    public function consultar(int $dias, CarbonImmutable $fechaReferencia): array
    {
        $clave = config('incendios.clave_nasa');
        if (! $clave) {
            throw new RuntimeException('Para consultar datos reales configura NASA_FIRMS_CLAVE en el archivo web/.env.');
        }

        // FIRMS consulta días de calendario UTC. Incluimos el día adicional para cubrir una ventana móvil completa.
        $fechaInicio = $fechaReferencia->subDays($dias)->startOfDay();
        $cantidadDias = $dias + 1;
        $claveCache = 'incendios:'.hash('sha256', $clave).':'.$dias.':'.$fechaReferencia->toDateString();

        return Cache::remember($claveCache, now()->addMinutes(config('incendios.minutos_cache')), function () use ($clave, $fechaInicio, $cantidadDias): array {
            $focos = [];

            // La API admite bloques de hasta cinco días. Siete días se consultan en dos bloques.
            for ($desplazamiento = 0; $desplazamiento < $cantidadDias; $desplazamiento += 5) {
                $diasBloque = min(5, $cantidadDias - $desplazamiento);
                $fechaBloque = $fechaInicio->addDays($desplazamiento)->toDateString();
                $urlConsulta = implode('/', [
                    'https://firms.modaps.eosdis.nasa.gov/api/area/csv',
                    rawurlencode($clave), config('incendios.fuente_nasa'),
                    $this->geografia->obtenerCajaConsulta(), $diasBloque, $fechaBloque,
                ]);

                $respuesta = Http::connectTimeout(8)->timeout(20)->get($urlConsulta);
                if (! $respuesta->successful()) {
                    throw new RuntimeException('NASA FIRMS no pudo completar la consulta. Intenta nuevamente más tarde.');
                }

                $focos = array_merge($focos, $this->normalizador->convertirCsv($respuesta->body()));
            }

            return $focos;
        });
    }
}
