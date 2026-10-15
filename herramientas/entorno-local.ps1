function Obtener-PhpGeoCruz {
    $phpConfigurado = $env:GEOCRUZ_PHP
    if ($phpConfigurado -and (Test-Path -LiteralPath $phpConfigurado)) { return $phpConfigurado }
    $phpLaragon = 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe'
    if (Test-Path -LiteralPath $phpLaragon) { return $phpLaragon }
    $comandoPhp = Get-Command php -CommandType Application -ErrorAction SilentlyContinue
    if ($comandoPhp) { return $comandoPhp.Source }
    throw 'No se encontró PHP. Configura GEOCRUZ_PHP con la ruta de tu php.exe.'
}

function Preparar-PhpGeoCruz {
    param([string]$RaizProyecto, [string]$EjecutablePhp)
    $carpetaPhp = Split-Path -Parent $EjecutablePhp
    $carpetaLocal = Join-Path $RaizProyecto '.herramientas'
    New-Item -ItemType Directory -Path $carpetaLocal -Force | Out-Null
    $configuracionPhp = Join-Path $carpetaLocal 'php.ini'
    $extensiones = @('openssl', 'curl', 'mbstring', 'fileinfo', 'pdo_sqlite', 'sqlite3')
    $lineas = @('date.timezone=America/La_Paz', 'memory_limit=512M', 'max_execution_time=60', 'display_errors=On', 'error_reporting=E_ALL')
    $carpetaExtensiones = Join-Path $carpetaPhp 'ext'
    if (Test-Path -LiteralPath $carpetaExtensiones) {
        $lineas += 'extension_dir="' + ($carpetaExtensiones -replace '\\', '/') + '"'
        foreach ($extension in $extensiones) {
            if (Test-Path -LiteralPath (Join-Path $carpetaExtensiones ('php_' + $extension + '.dll'))) { $lineas += 'extension=' + $extension }
        }
        # Reutilizar el archivo evita que dos terminales escriban su configuración a la vez.
        if (-not (Test-Path -LiteralPath $configuracionPhp)) {
            Set-Content -LiteralPath $configuracionPhp -Value $lineas -Encoding ascii
        }
        $env:PHPRC = $configuracionPhp
    }
}
