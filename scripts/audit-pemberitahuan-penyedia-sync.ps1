param(
    [int]$Limit = 50,
    [switch]$Json,
    [switch]$SaveReport,
    [string]$OutputPath
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

if ($SaveReport -and -not $OutputPath) {
    $timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
    $OutputPath = ".\storage\app\audits\pemberitahuan-penyedia-sync-$timestamp.json"
}

if ($OutputPath) {
    $Json = $true
}

$commandArgs = @(
    'artisan'
    'audit:pemberitahuan-penyedia-sync'
    "--limit=$Limit"
)

if ($Json) {
    $commandArgs += '--json'
}

Push-Location $repoRoot
try {
    if ($OutputPath) {
        $resolvedOutputPath = if ([System.IO.Path]::IsPathRooted($OutputPath)) {
            $OutputPath
        } else {
            Join-Path $repoRoot $OutputPath
        }

        $outputDir = Split-Path -Parent $resolvedOutputPath
        if ($outputDir -and -not (Test-Path $outputDir)) {
            New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
        }

        $output = & $php @baseArgs @commandArgs
        $exitCode = $LASTEXITCODE

        if ($exitCode -ne 0) {
            throw "Audit gagal dijalankan. Exit code: $exitCode"
        }

        [System.IO.File]::WriteAllText($resolvedOutputPath, ($output -join [Environment]::NewLine))
        Write-Output "Audit tersimpan di: $resolvedOutputPath"
        Write-Output $output
        exit 0
    }

    & $php @baseArgs @commandArgs
    exit $LASTEXITCODE
} finally {
    Pop-Location
}
