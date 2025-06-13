#!/bin/bash

# Mode par défaut : dev
MODE="dev"

if [[ "$1" == "--prod" ]]; then
  MODE="prod"
elif [[ "$1" == "--dev" ]]; then
  MODE="dev"
fi

echo "🔧 Setup Laravel project in '$MODE' mode..."

# Nettoyage
echo "🧹 Nettoyage des anciens dossiers..."
rm -rf node_modules
rm -rf public/js public/css
rm -rf resources/vendor/admin-lte/node_modules

# Installation PHP
echo "📦 Installation des dépendances PHP avec Composer..."
if [[ "$MODE" == "prod" ]]; then
  composer install --no-dev --optimize-autoloader
else
  composer install
fi

# Installation JS (racine)
echo "📦 Installation des dépendances JS à la racine..."
npm install
npm audit fix

# Compilation des assets selon le mode
if [[ "$MODE" == "prod" ]]; then
  echo "⚙️ Compilation des assets en mode production..."
  npm run production
else
  echo "⚙️ Compilation des assets en mode développement..."
  npm run dev
fi

# AdminLTE
echo "📁 Installation des dépendances pour AdminLTE..."
cd resources/vendor/admin-lte
npm install
npm audit fix
npm run production
cd ../../..

# Lien vers /storage
echo "🔗 Création du lien symbolique Laravel..."
php artisan storage:link

# Migration et seed
if [[ "$MODE" == "prod" ]]; then
  echo "🗄️ Migration de la base de données (prod)..."
  php artisan migrate --force

  echo "🌱 Seeders de configuration (prod)..."
  php artisan db:seed --class=RolesTableSeeder --force
  php artisan db:seed --class=PermissionsTableSeeder --force
  php artisan db:seed --class=RoleHasPermissionsTableSeeder --force
else
  echo "🗄️ Migration + seed complet (dev)..."
  php artisan migrate --seed
fi

echo "✅ Setup terminé en mode '$MODE'."
