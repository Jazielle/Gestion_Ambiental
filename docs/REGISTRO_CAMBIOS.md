# Registro de cambios de GeoCruz

Fecha: 8 de octubre de 2026. Implementación local autorizada por Josué. No se ejecutaron comandos Git/GitHub.

## Documentación académica añadida (Cursor · solo docs/)

Se elaboraron documentos editables para la entrega, sin modificar código ni ejecutar Git/GitHub:

- docs/INDICE.md
- docs/01_ALCANCE_Y_ESTADO.md
- docs/02_HISTORIAS_DE_USUARIO.md
- docs/03_CASO_DE_USO.md
- docs/04_RECORRIDO_CODIGO_Y_API.md
- docs/05_GUIA_PRUEBA_MANUAL.md
- docs/06_GUION_PDF_EVIDENCIAS.md
- docs/07_BORRADORES_ISSUE_Y_PR.md
- docs/08_TRAZABILIDAD_Y_PLANIFICACION.md

También se actualizó este registro. Las evidencias de GitHub, aprobación de Jaziel, PERT/CPM/Planner aprobados y pruebas NASA/Google reales siguen pendientes.

## Archivos propios creados

Se crearon y ajustaron durante la implementación los siguientes archivos. Las modificaciones posteriores de un archivo nuevo siguen perteneciendo a esta creación inicial.

- .gitignore
- README.md
- docs/MAPA_DEL_CODIGO.md
- docs/PROMPT_PARA_CURSOR.md
- docs/REGISTRO_CAMBIOS.md
- docs/RESULTADOS_PRUEBAS.md
- docs/evidencias/demo-escritorio.png
- docs/evidencias/demo-pantalla-pequena.png
- herramientas/entorno-local.ps1
- herramientas/iniciar-demo.ps1
- herramientas/probar-demo.ps1
- herramientas/verificar-interfaz.cjs
- movil/README.md
- web/app/Modulos/Incendios/ControladorIncendios.php
- web/app/Modulos/Incendios/DatosDemostracion.php
- web/app/Modulos/Incendios/GeografiaSantaCruz.php
- web/app/Modulos/Incendios/NormalizadorFirms.php
- web/app/Modulos/Incendios/ServicioIncendios.php
- web/app/Modulos/Incendios/ServicioNasaFirms.php
- web/app/Modulos/Incendios/rutasApi.php
- web/app/Modulos/Incendios/rutasWeb.php
- web/composer.lock
- web/config/incendios.php
- web/public/modulos/incendios/formato.js
- web/public/modulos/incendios/geocruz.svg
- web/public/modulos/incendios/incendios.css
- web/public/modulos/incendios/incendios.js
- web/public/modulos/incendios/mapaDemostracion.js
- web/public/modulos/incendios/mapaGoogle.js
- web/resources/views/incendios/componentes/detalle.blade.php
- web/resources/views/incendios/componentes/filtros.blade.php
- web/resources/views/incendios/componentes/resumen.blade.php
- web/resources/views/incendios/pagina.blade.php
- web/routes/api.php
- web/tests/Feature/Incendios/ConsultaIncendiosTest.php

## Archivos del esqueleto Laravel editados

- web/bootstrap/app.php
- web/routes/web.php
- web/composer.json
- web/.env.example
- web/README.md

Se cambió el nombre del proyecto, las rutas, el arranque de API y los comandos Composer. Se usan sesión y caché en archivos. .env.example no tiene claves reales. composer.lock fija las versiones y se actualizó después de ajustar composer.json.

## Archivos de ejemplo eliminados

- web/resources/views/welcome.blade.php
- web/tests/Feature/ExampleTest.php
- web/tests/Unit/ExampleTest.php
- web/resources/js/app.js
- web/resources/js/bootstrap.js
- web/resources/css/app.css
- web/vite.config.js
- web/package.json

Se retiraron la bienvenida, pruebas de ejemplo y configuración/assets Vite porque esta demo usa Blade y archivos estáticos directamente.

## Datos compartidos existentes

Se reutilizaron compartido/santa-cruz.geojson y compartido/fuentes-geograficas.json, creados antes de confirmar Laravel.

## Configuración local y dependencias

Se descargó Composer en .herramientas/composer.phar y se creó .herramientas/php.ini. Se creó web/.env y se generó APP_KEY local sin mostrar su valor. Se instalaron las dependencias en web/vendor/. Esos archivos se excluyen del repositorio. No se cambió la configuración global de Laragon.

## Estructura estándar importada

También se importó el esqueleto oficial de Laravel. Sus carpetas estándar conservan la organización y los nombres del framework. Inventario final de archivos estándar presentes, sin dependencias ni archivos generados:

- web/.editorconfig
- web/.gitattributes
- web/.gitignore
- web/.phpunit.result.cache
- web/app/Http/Controllers/Controller.php
- web/app/Models/User.php
- web/app/Providers/AppServiceProvider.php
- web/artisan
- web/bootstrap/cache/.gitignore
- web/bootstrap/providers.php
- web/config/app.php
- web/config/auth.php
- web/config/cache.php
- web/config/database.php
- web/config/filesystems.php
- web/config/logging.php
- web/config/mail.php
- web/config/queue.php
- web/config/services.php
- web/config/session.php
- web/database/.gitignore
- web/database/factories/UserFactory.php
- web/database/migrations/0001_01_01_000000_create_users_table.php
- web/database/migrations/0001_01_01_000001_create_cache_table.php
- web/database/migrations/0001_01_01_000002_create_jobs_table.php
- web/database/seeders/DatabaseSeeder.php
- web/phpunit.xml
- web/public/.htaccess
- web/public/favicon.ico
- web/public/index.php
- web/public/robots.txt
- web/routes/console.php
- web/storage/app/.gitignore
- web/storage/app/private/.gitignore
- web/storage/app/public/.gitignore
- web/storage/framework/.gitignore
- web/storage/framework/cache/.gitignore
- web/storage/framework/testing/.gitignore
- web/tests/TestCase.php
