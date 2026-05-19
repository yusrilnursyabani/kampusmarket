Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
Set-Location $projectRoot

Write-Host "== KampusMarket: sync after pull ==" -ForegroundColor Cyan

if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    throw "Composer not found in PATH. Install Composer, then re-run this script."
}

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    throw "PHP not found in PATH. Ensure Laragon/PHP is available, then re-run this script."
}

composer run sync
exit $LASTEXITCODE
