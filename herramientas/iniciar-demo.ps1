param([int]$Puerto = 8123)
$ErrorActionPreference = 'Stop'
. (Join-Path $PSScriptRoot 'entorno-local.ps1')
$raizProyecto = Split-Path -Parent $PSScriptRoot
$carpetaWeb = Join-Path $raizProyecto 'web'
$ejecutablePhp = Obtener-PhpGeoCruz
Preparar-PhpGeoCruz -RaizProyecto $raizProyecto -EjecutablePhp $ejecutablePhp
if (-not (Test-Path -LiteralPath (Join-Path $carpetaWeb 'vendor\autoload.php'))) { throw 'Faltan las dependencias. Sigue la instalación del README.' }
if (-not (Test-Path -LiteralPath (Join-Path $carpetaWeb '.env'))) {
    Copy-Item -LiteralPath (Join-Path $carpetaWeb '.env.example') -Destination (Join-Path $carpetaWeb '.env')
}
Push-Location $carpetaWeb
try {
    $entorno = Get-Content -LiteralPath '.env' -Raw
    if ($entorno -match '(?m)^APP_KEY=\s*$') {
        & $ejecutablePhp artisan key:generate --force
        if ($LASTEXITCODE -ne 0) { throw 'No se pudo generar APP_KEY.' }
    }
    Write-Host "GeoCruz disponible en http://127.0.0.1:$Puerto"
    Write-Host 'Detén la demo con Ctrl+C. Fuente inicial: datos simulados.'
    # Ejecutar PHP directamente evita un problema del subproceso de artisan serve en esta instalación.
    Push-Location (Join-Path $carpetaWeb 'public')
    try {
        & $ejecutablePhp -S "127.0.0.1:$Puerto" ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
    } finally { Pop-Location }
} finally { Pop-Location }
