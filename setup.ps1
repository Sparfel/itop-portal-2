param (
    [string]$Mode = "dev"
)

if ($args.Length -gt 0 -and $args[0] -eq "--prod") {
    $Mode = "prod"
}

Write-Host "⚙️  Setup Laravel en mode '$Mode'..."

# Nettoyage
Write-Host "🧹 Nettoyage des dépendances JS et fichiers compilés..."
Remove-Item -Recurse -Force node_modules, "public/js", "public/css", "resources/vendor/admin-lte/node_modules" -ErrorAction SilentlyContinue

# Installation des dépendances PHP
Write-Host "📦 Installation des dépendances PHP..."
if ($Mode -eq "prod") {
    composer install --no-dev --optimize-autoloader
} else {
    composer install
}

# Installation JS (racine)
Write-Host "📦 Installation des dépendances JS à la racine..."
npm install
npm audit fix

# AdminLTE
Write-Host "📁 Installation des dépendances JS AdminLTE..."
Set-Location "resources/vendor/admin-lte"
npm install
npm audit fix
npm run production
Set-Location "../../../"

# Compilation des assets Laravel
Write-Host "⚙️ Compilation des assets Laravel ($Mode)..."
if ($Mode -eq "prod") {
    npm run production
} else {
    npm run dev
}

# Lien symbolique
Write-Host "🔗 Création du lien storage..."
php artisan storage:link

# Migration & seed
if ($Mode -eq "prod") {
    Write-Host "🗄️  Migration base (force)..."
    php artisan migrate --force

    Write-Host "🌱 Seeders de config (roles/permissions)..."
    php artisan db:seed --class=RolesTableSeeder --force
    php artisan db:seed --class=PermissionsTableSeeder --force
    php artisan db:seed --class=RoleHasPermissionsTableSeeder --force
} else {
    Write-Host "🗄️  Migration + seed complet (dev)..."
    php artisan migrate:fresh --seed
}

Write-Host "✅ Setup Laravel terminé en mode '$Mode'."
