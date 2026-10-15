# GeoCruz

Demo académica de focos de calor en Santa Cruz, Bolivia. Josué desarrolla la web; Jaziel desarrolla la aplicación móvil y revisa la web. La demo usa Laravel 12 y Blade, sin base de datos ni compilación de JavaScript.

## Ejecutar en esta computadora

Abre PowerShell en `C:\Users\cjosu\Desktop\GeoCruz`:

```powershell
powershell -ExecutionPolicy Bypass -File .\herramientas\iniciar-demo.ps1
```

Visita http://127.0.0.1:8123. Mantén la terminal abierta y detén el servidor con Ctrl+C. Puedes agregar `-Puerto 8124` si el puerto está ocupado. El script utiliza PHP de Laragon con una configuración local; no modifica la instalación global.

Para ejecutar las pruebas:

```powershell
powershell -ExecutionPolicy Bypass -File .\herramientas\probar-demo.ps1
```

## Instalación en otra computadora

Necesitas PHP compatible con Laravel 12 (8.2 o superior) y Composer. Usa PHP 8.3 como referencia y habilita openssl, curl, mbstring, fileinfo, dom, xml y xmlwriter. No necesitas Node. Desde `web/`:

```powershell
composer install --prefer-dist
Copy-Item .env.example .env
php artisan key:generate
php artisan serve
```

Conserva `compartido/` junto a `web/`; el servidor utiliza ese límite geográfico. Cada computadora genera su APP_KEY. Para usar los scripts con otro PHP, establece `$env:GEOCRUZ_PHP` con la ruta de tu `php.exe`.

## Característica demostrada

- Períodos móviles de 24 horas, 72 horas y 7 días.
- Filtro por confianza del sensor.
- Mapa, marcadores seleccionables y listado por fecha.
- Detalle de coordenadas, fecha/hora, satélite, instrumento, confianza y potencia radiativa (FRP).
- Resumen de resultados visibles y estados de carga, error y consulta vacía.

La fuente inicial **Demostración** contiene focos inventados, identificados como simulados. El mapa local utiliza un límite real simplificado y funciona sin Google ni conexión externa. Este modo verifica el flujo local, no una conexión real a NASA o Google.

## Activar las integraciones reales

1. Solicita tu MAP_KEY en https://firms.modaps.eosdis.nasa.gov/api/area/ y configura `NASA_FIRMS_CLAVE` en `web/.env`.
2. Configura una clave de Google con Maps JavaScript API habilitada y la facturación requerida por tu cuenta. Restringe su uso a tus orígenes autorizados y a esa API.
3. Colócala en `GOOGLE_MAPS_CLAVE`. `GOOGLE_MAPS_ID=DEMO_MAP_ID` permite probar marcadores avanzados; para publicación configura tu propio identificador.
4. Reinicia Laravel. Google Maps se utiliza si existe una clave válida. Elige **NASA FIRMS** en el selector de fuente para consultar detecciones reales.

La clave NASA permanece en el servidor. La clave de Maps se utiliza en el navegador, según el diseño de Google, y necesita restricciones. `.env` está excluido del control de versiones; no copies claves privadas a capturas o documentación.

Las consultas NASA usan VIIRS Suomi NPP, caché de cinco minutos y el polígono departamental para descartar puntos fuera de Santa Cruz. FIRMS recibe días de calendario UTC: consultamos bloques de hasta cinco días y aplicamos después el período móvil exacto. La interfaz presenta horas de Bolivia (UTC−4).

Si NASA falla, el sistema muestra un error y no sustituye sus datos por simulados. Si Google Maps falla, se indica que se usa el mapa de demostración. Un CSV válido con encabezados y ninguna detección es un resultado vacío.

## Código y API

El código propio se organiza por funcionalidad, con nombres en español. Las interfaces de Laravel, PHP, JavaScript, NASA y Google conservan sus nombres obligatorios. Lee `docs/MAPA_DEL_CODIGO.md` para saber qué archivo tocar.

`GET /api/incendios?dias=1&fuente=demo` acepta `dias=1|3|7` y `fuente=demo|nasa`. Devuelve `focos`, `resumen` y `consulta`. Cada foco contiene `identificador`, `latitud`, `longitud`, `fechaDeteccion` (ISO 8601 con zona), `satelite`, `instrumento`, `confianza` y `potenciaRadiativa` (MW o null).

`GET /api/incendios/limite` devuelve el GeoJSON. Parámetros inválidos producen 422 y fuente no disponible, 503, ambos con `mensaje`. La futura aplicación móvil puede consumir esta misma API.

## Documentación y evidencias

El prompt de Cursor está en `docs/PROMPT_PARA_CURSOR.md`. Los cambios se enumeran en `docs/REGISTRO_CAMBIOS.md` y las comprobaciones en `docs/RESULTADOS_PRUEBAS.md`.

Los integrantes ejecutan todas las operaciones Git/GitHub manualmente. El código local no acredita que se crearon issues, ramas, commits o PR, ni revisiones o merges. Esos pasos y sus capturas quedan pendientes de realizarlos con el equipo. Documentar después del desarrollo no equivale a tener planificación previa.

## Fuentes y alcance

- NASA: https://firms.modaps.eosdis.nasa.gov/api/area/
- Google: https://developers.google.com/maps/documentation/javascript/advanced-markers/start
- Laravel: https://laravel.com/docs/12.x
- Límite: geoBoundaries gbOpen / GeoBolivia, año representado 2015. Distribución CC BY 4.0 y fuente original de dominio público. Metadatos en `compartido/fuentes-geograficas.json`.

Un foco térmico no confirma un incendio. La confianza pertenece al sensor y no expresa gravedad. La geometría simplificada sirve para uso académico. La demo no implementa autenticación, persistencia, alertas ni aplicación móvil; `movil/` reserva el trabajo de Jaziel. Las integraciones reales necesitan claves y no se consideran verificadas con pruebas simuladas.
