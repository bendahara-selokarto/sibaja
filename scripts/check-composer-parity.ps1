param(
    [switch]$Json
)

$ErrorActionPreference = 'Stop'

$repoRoot = Split-Path -Parent $PSScriptRoot
$composerJsonPath = Join-Path $repoRoot 'composer.json'
$lockPath = Join-Path $repoRoot 'composer.lock'
$installedPath = Join-Path $repoRoot 'vendor\composer\installed.json'
$platformCheckPath = Join-Path $repoRoot 'vendor\composer\platform_check.php'

if (-not (Test-Path $lockPath)) {
    throw "composer.lock tidak ditemukan di $lockPath"
}

if (-not (Test-Path $installedPath)) {
    throw "vendor/composer/installed.json tidak ditemukan. Jalankan composer install terlebih dahulu."
}

function Get-PackageMapFromComposerFile {
    param(
        [string]$Path
    )

    $packages = @{}
    $currentName = $null

    foreach ($line in Get-Content $Path) {
        if (-not $currentName -and $line -match '^\s{12}"name"\s*:\s*"([^"]+)"') {
            $currentName = $matches[1]
            continue
        }

        if ($currentName -and $line -match '^\s{12}"version"\s*:\s*"([^"]+)"') {
            if ($currentName -ne '__root__') {
                $packages[$currentName] = $matches[1]
            }

            $currentName = $null
        }
    }

    return $packages
}

$lockPackages = Get-PackageMapFromComposerFile -Path $lockPath
$installedPackages = Get-PackageMapFromComposerFile -Path $installedPath

$missingInVendor = @(
    $lockPackages.Keys |
        Where-Object { -not $installedPackages.ContainsKey($_) } |
        Sort-Object |
        ForEach-Object {
            [pscustomobject]@{
                name = $_
                locked_version = $lockPackages[$_]
            }
        }
)

$extraInVendor = @(
    $installedPackages.Keys |
        Where-Object { -not $lockPackages.ContainsKey($_) } |
        Sort-Object |
        ForEach-Object {
            [pscustomobject]@{
                name = $_
                installed_version = $installedPackages[$_]
            }
        }
)

$versionMismatch = @(
    $lockPackages.Keys |
        Where-Object {
            $installedPackages.ContainsKey($_) -and $installedPackages[$_] -ne $lockPackages[$_]
        } |
        Sort-Object |
        ForEach-Object {
            [pscustomobject]@{
                name = $_
                locked_version = $lockPackages[$_]
                installed_version = $installedPackages[$_]
            }
        }
)

$platformRequirement = $null
if (Test-Path $platformCheckPath) {
    $platformCheck = Get-Content $platformCheckPath -Raw
    $match = [regex]::Match($platformCheck, 'require a PHP version "(?<php>[^"]+)"')
    if ($match.Success) {
        $platformRequirement = $match.Groups['php'].Value
    }
}

$rootPhpRequirement = $null
if (Test-Path $composerJsonPath) {
    foreach ($line in Get-Content $composerJsonPath) {
        if ($line -match '^\s{8}"php"\s*:\s*"([^"]+)"') {
            $rootPhpRequirement = $matches[1]
            break
        }
    }
}

$stalePlatformCheck = $false
if (
    $versionMismatch.Count -eq 0 -and
    $missingInVendor.Count -eq 0 -and
    $extraInVendor.Count -eq 0 -and
    $platformRequirement -eq '>= 8.4.0' -and
    $rootPhpRequirement -eq '^8.2'
) {
    $stalePlatformCheck = $true
}

$summary = [pscustomobject]@{
    lock_package_count = $lockPackages.Count
    installed_package_count = $installedPackages.Count
    missing_in_vendor_count = $missingInVendor.Count
    extra_in_vendor_count = $extraInVendor.Count
    version_mismatch_count = $versionMismatch.Count
    root_php_requirement = $rootPhpRequirement
    vendor_platform_requirement = $platformRequirement
    stale_platform_check = $stalePlatformCheck
}

if ($Json) {
    [pscustomobject]@{
        summary = $summary
        version_mismatches = $versionMismatch
        missing_in_vendor = $missingInVendor
        extra_in_vendor = $extraInVendor
    } | ConvertTo-Json -Depth 6
} else {
    $summary | Format-List

    if ($versionMismatch.Count -gt 0) {
        Write-Host "`nVersion mismatch:"
        $versionMismatch | Select-Object -First 20 | Format-Table -AutoSize
    }

    if ($missingInVendor.Count -gt 0) {
        Write-Host "`nMissing in vendor:"
        $missingInVendor | Select-Object -First 20 | Format-Table -AutoSize
    }

    if ($extraInVendor.Count -gt 0) {
        Write-Host "`nExtra in vendor:"
        $extraInVendor | Select-Object -First 20 | Format-Table -AutoSize
    }
}

if (
    $versionMismatch.Count -gt 0 -or
    $missingInVendor.Count -gt 0 -or
    $extraInVendor.Count -gt 0 -or
    $stalePlatformCheck
) {
    exit 1
}
