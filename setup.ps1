# Mode par défaut : dev
$mode = "dev"
if ($args.Count -gt 0) {
    if ($args[0] -eq "--prod") {
        $mode = "prod"
    }
}

Write-Host "🔧 Setup Laravel project in '$mode' mode..."

# Nettoyage
Write-Host "🧹 Nettoyage des anciens dossiers..."
Remove-Item -Recurse -Force node_modules, public\js, public\css
Remove-Item -Recurse -Force resources\vendor\admin-lte\node_modules

# Composer
Write-Host "📦 Installation des dépendances PHP avec Composer..."
if ($mode -eq "prod") {
    composer install --no-dev --optimize-autoloader
} else {
    composer install
}

# npm (racine)
Write-Host "📦 Installation des dépendances JS à la racine..."
npm install
npm audit fix

# Compilation
if ($mode -eq "prod") {
    Write-Host "⚙️ Compilation des assets en mode production..."
    npm run production
} else {
    Write-Host "⚙️ Compilation des assets en mode développement..."
    npm run dev
}

# AdminLTE
Write-Host "📁 Installation des dépendances pour AdminLTE..."
Set-Location resources\vendor\admin-lte
npm install
npm audit fix
npm run production
Set-Location ..\..\..

# Lien symbolique
Write-Host "🔗 Création du lien symbolique Laravel..."
php artisan storage:link

# Migration + seed
if ($mode -eq "prod") {
    Write-Host "🗄️ Migration de la base de données (prod)..."
    php artisan migrate --force

    Write-Host "🌱 Seeders de configuration (prod)..."
    php artisan db:seed --class=RolesTableSeeder --force
    php artisan db:seed --class=PermissionsTableSeeder --force
    php artisan db:seed --class=RoleHasPermissionsTableSeeder --force
} else {
    Write-Host "🗄️ Migration + seed complet (dev)..."
    php artisan migrate --seed
}

Write-Host "✅ Setup terminé en mode '$mode'."
