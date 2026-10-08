<?php

namespace App\Modulos\Incendios;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RuntimeException;

class ControladorIncendios
{
    public function mostrarPagina(): View
    {
        return view('incendios.pagina', [
            'configuracionMapa' => [
                'claveGoogle' => config('incendios.clave_google_maps'),
                'identificadorMapa' => config('incendios.identificador_mapa'),
            ],
        ]);
    }

    public function consultarFocos(Request $solicitud, ServicioIncendios $servicio): JsonResponse
    {
        $validacion = Validator::make($solicitud->query(), [
            'dias' => ['sometimes', 'required', 'integer', 'in:1,3,7'],
            'fuente' => ['sometimes', 'required', 'in:demo,nasa'],
        ]);

        if ($validacion->fails()) {
            return response()->json(['mensaje' => 'Consulta inválida. Usa dias=1, 3 o 7 y fuente=demo o nasa.'], 422);
        }

        try {
            return response()->json($servicio->consultar((int) $solicitud->query('dias', 1), $solicitud->query('fuente', 'demo')));
        } catch (ConnectionException) {
            return response()->json(['mensaje' => 'No se pudo conectar con NASA FIRMS. Comprueba tu conexión y vuelve a intentarlo.'], 503);
        } catch (RuntimeException $error) {
            return response()->json(['mensaje' => $error->getMessage()], 503);
        }
    }

    public function consultarLimite(GeografiaSantaCruz $geografia): JsonResponse
    {
        return response()->json($geografia->obtenerLimite());
    }
}
