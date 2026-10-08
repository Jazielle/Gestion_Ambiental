<?php

// Configuración del módulo. Las claves privadas se leen únicamente en el servidor.
return [
    'clave_nasa' => env('NASA_FIRMS_CLAVE', ''),
    'fuente_nasa' => 'VIIRS_SNPP_NRT',
    'clave_google_maps' => env('GOOGLE_MAPS_CLAVE', ''),
    'identificador_mapa' => env('GOOGLE_MAPS_ID', 'DEMO_MAP_ID'),
    'archivo_limite' => base_path('../compartido/santa-cruz.geojson'),
    'minutos_cache' => 5,
];
