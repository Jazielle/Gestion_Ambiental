# Comprobaciones de la demo GeoCruz

Fecha de comprobación: 8 de octubre de 2026. Ejecutadas por Codex en el entorno local de Josué. Estas comprobaciones no son una revisión o aprobación de Jaziel.

## Servidor y módulo

PHP 8.3.30 y Laravel 12.69.3. Once pruebas automatizadas pasaron con 59 comprobaciones. Se verificó:

- Página accesible sin exponer la clave privada NASA.
- Datos simulados dentro de Santa Cruz y de las últimas 24 horas.
- Siete días muestran más focos que un día.
- Rechazo de períodos/fuentes inválidos y parámetros enviados como arreglos.
- Consulta real sin clave devuelve 503, sin datos simulados.
- GeoJSON departamental accesible.
- Normalización de hora UTC con ceros iniciales, confianza y coordenadas.
- Duplicados y detecciones fuera del período/territorio descartados.
- Consulta de siete días dividida en bloques de cinco y tres días de calendario.
- Caché evita repetir las solicitudes NASA.
- CSV inválido y servicio NASA caído devuelven errores claros.
- Polígono distingue Santa Cruz de La Paz y de coordenadas fuera de Bolivia.

Las respuestas NASA de estas pruebas están controladas mediante Http::fake. No se usaron claves ni se verificó una consulta real a NASA.

Los archivos PHP propios y JavaScript pasaron comprobación de sintaxis. Composer validó su configuración y archivo de bloqueo. La página y la API respondieron correctamente a solicitudes HTTP locales.

## Navegador

Verificación automatizada con Playwright y Microsoft Edge, en ventanas aisladas de 1440 × 1000 y 390 × 844 píxeles. Sin errores de JavaScript ni desbordamiento horizontal. Se verificaron marcadores, selección y detalle, período de siete días, filtro de confianza y resumen, zoom/restablecer, NASA sin clave, consulta vacía controlada, actualización y pantalla pequeña.

El conjunto simulado devuelve 7 focos en 24 horas y 16 en siete días. Las capturas incluyen un aviso visible de simulación:

- evidencias/demo-escritorio.png
- evidencias/demo-pantalla-pequena.png

Estas capturas prueban la funcionalidad local. La segunda es una vista web adaptable, no una aplicación móvil de Jaziel.

## Reproducir

Desde la raíz ejecuta herramientas/iniciar-demo.ps1 y visita http://127.0.0.1:8123. En otra terminal ejecuta herramientas/probar-demo.ps1.

herramientas/verificar-interfaz.cjs es una herramienta opcional de comprobación y requiere Node, Playwright y un navegador compatible. GEOCRUZ_URL permite cambiar la dirección y GEOCRUZ_NAVEGADOR la ruta del navegador. No es una dependencia para utilizar la demo.

## Pendientes para la entrega

- Conexiones reales a NASA y Google con claves autorizadas.
- Prueba y aprobación de Jaziel con evidencia propia.
- Planificación aprobada y vínculo a historia de usuario y caso de uso.
- Issue, rama, commits, PR y merge realizados manualmente por los integrantes.
- Capturas reales de esas operaciones y enlace al repositorio.
- PDF de evidencias armado con la documentación y capturas reales.

No se efectuaron operaciones Git/GitHub ni se generaron evidencias ficticias del repositorio.
