# Guía para localizar el código de GeoCruz

La funcionalidad implementada es Incendios. No hay inicio de sesión; cuando se solicite tendrá su propio módulo. La organización permite cambiar una responsabilidad sin mezclarla con otras funcionalidades.

## Servidor

Todo el código propio del servidor está en `web/app/Modulos/Incendios/`:

- `rutasWeb.php`: direcciones de la pantalla.
- `rutasApi.php`: direcciones JSON para web y móvil.
- `ControladorIncendios.php`: solicitud, validación y respuesta.
- `ServicioIncendios.php`: coordinación, filtros de tiempo/territorio y resumen.
- `ServicioNasaFirms.php`: consultas externas, caché y bloques de fechas.
- `NormalizadorFirms.php`: CSV externo a campos en español, validación y duplicados.
- `DatosDemostracion.php`: escenarios inventados para pruebas.
- `GeografiaSantaCruz.php`: límite y pertenencia al departamento.

`web/config/incendios.php` reúne la configuración. `web/routes/web.php` y `web/routes/api.php` conectan Laravel al módulo y `web/bootstrap/app.php` registra esos grupos de rutas.

## Pantalla

`web/resources/views/incendios/pagina.blade.php` arma la página. En `componentes/` están `filtros.blade.php`, `resumen.blade.php` y `detalle.blade.php`.

## Interacción y apariencia

En `web/public/modulos/incendios/`:

- `incendios.js`: elementos, estado, presentación, consultas y eventos, en cinco secciones numeradas.
- `mapaDemostracion.js`: dibujo local, zoom y marcadores.
- `mapaGoogle.js`: integración Google y marcadores avanzados.
- `formato.js`: presentación de fechas, potencia y confianza.
- `incendios.css`: base, estructura, filtros, indicadores, mapa/listado y adaptación a pantallas pequeñas.
- `geocruz.svg`: símbolo de la aplicación.

Para cambiar un texto edita Blade; para cambiar colores o distribución edita CSS; para cambiar un filtro revisa JavaScript y su validación en el controlador. Los datos simulados se cambian en `DatosDemostracion.php`.

## Pruebas y herramientas

`web/tests/Feature/Incendios/ConsultaIncendiosTest.php` prueba la página, API, fechas, geografía, CSV, caché y errores con respuestas NASA controladas, sin claves reales.

`herramientas/iniciar-demo.ps1` inicia Laravel; `probar-demo.ps1` ejecuta las pruebas y `entorno-local.ps1` prepara PHP. `compartido/` contiene límites y metadatos.

`web/vendor/` contiene dependencias: no lo edites. Los archivos estándar e interfaces obligatorias de Laravel conservan sus nombres originales.

## Recorrido

Filtro → `incendios.js` → `rutasApi.php` → `ControladorIncendios` → `ServicioIncendios` → demostración o `ServicioNasaFirms` → filtro de fecha y territorio → JSON → mapa, listado y resumen.

Después de cada cambio registra qué archivos creaste, editaste o eliminaste y comprueba el comportamiento afectado.
