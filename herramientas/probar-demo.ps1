$ErrorActionPreference = 'Stop'
. (Join-Path $PSScriptRoot 'entorno-local.ps1')
$raizProyecto = Split-Path -Parent $PSScriptRoot
$ejecutablePhp = Obtener-PhpGeoCruz
Preparar-PhpGeoCruz -RaizProyecto $raizProyecto -EjecutablePhp $ejecutablePhp
Push-Location (Join-Path $raizProyecto 'web')
try {
    & $ejecutablePhp artisan test
    $resultadoPruebas = $LASTEXITCODE
} finally { Pop-Location }
exit $resultadoPruebas
