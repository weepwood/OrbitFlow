$ErrorActionPreference = 'Stop'
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { throw '请先安装 Composer 2' }
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { throw '请先安装 PHP 8.2+' }
if (-not (Get-Command npm.cmd -ErrorAction SilentlyContinue)) { throw '请先安装 Node.js / npm' }
$extensions = @(php -m)
foreach ($ext in @('mbstring', 'pdo_sqlite')) {
    if ($extensions -notcontains $ext) { throw "缺少 PHP 扩展: $ext" }
}
if (-not (Test-Path '.env')) { Copy-Item '.env.example' '.env' }
if (-not (Test-Path 'database/database.sqlite')) { New-Item -ItemType File 'database/database.sqlite' | Out-Null }
composer install
if ($LASTEXITCODE -ne 0) { throw 'Composer 安装失败' }
if ((Get-Content '.env' -Raw) -match '(?m)^APP_KEY=\s*$') {
    php artisan key:generate
    if ($LASTEXITCODE -ne 0) { throw '密钥生成失败' }
}
php artisan migrate --force
if ($LASTEXITCODE -ne 0) { throw '数据库迁移失败' }
npm.cmd install
if ($LASTEXITCODE -ne 0) { throw 'npm 安装失败' }
npm.cmd run build
if ($LASTEXITCODE -ne 0) { throw '前端构建失败' }
Write-Host '初始化完成。运行 php artisan serve 启动应用。'
