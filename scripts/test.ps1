param(
    [switch]$Artisan,
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$Args
)

$ErrorActionPreference = 'Stop'

$repoRoot = Split-Path -Parent $PSScriptRoot

$phpCandidates = @(
    $env:SIBAJA_PHP,
    'C:\Users\lenovo\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe',
    'C:\xampp\php\php.exe',
    'C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe',
    'C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe',
    'C:\php\php.exe'
) | Where-Object { $_ }

$php = $phpCandidates | Where-Object { Test-Path $_ } | Select-Object -First 1

if (-not $php) {
    throw "PHP runtime tidak ditemukan. Set env SIBAJA_PHP ke path php.exe lokal."
}

$phpDir = Split-Path -Parent $php
$extensionDir = Join-Path $phpDir 'ext'

$baseArgs = @(
    '-n'
    "-dextension_dir=$extensionDir"
    '-dextension=php_openssl.dll'
    '-dextension=php_mbstring.dll'
    '-dextension=php_fileinfo.dll'
    '-dextension=php_pdo_sqlite.dll'
    '-dextension=php_sqlite3.dll'
)

$bcmathExtension = Join-Path $extensionDir 'php_bcmath.dll'
if (Test-Path $bcmathExtension) {
    $baseArgs += '-dextension=php_bcmath.dll'
}

$commandArgs = if ($Artisan) {
    @('artisan', 'test') + $Args
} else {
    @('vendor\bin\phpunit') + $Args
}

Push-Location $repoRoot
try {
    & $php @baseArgs @commandArgs
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
