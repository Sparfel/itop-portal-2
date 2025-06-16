#!/bin/bash

# Par défaut : mode dev
MODE="dev"

if [[ "$1" == "--prod" ]]; then
  MODE="prod"
fi

echo "⚙️  Setup Laravel en mode '$MODE'..."

# Nettoyage
echo "🧹 Nettoyage des dépendances JS et fichiers compilés..."
rm -rf node_modules
rm -rf resources/vendor/admin-lte/node_modules
rm -rf public/js public/css

# Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
if [[ "$MODE" == "prod" ]]; then
  composer install --no-dev --optimize-autoloader
else
  composer install
fi

# Installation JS (racine)
echo "📦 Installation des dépendances JS à la racine..."
npm install
npm audit fix

# AdminLTE
echo "📁 Installation des dépendances JS AdminLTE..."
cd resources/vendor/admin-lte
npm install
npm audit fix
npm run production
cd ../../..

# Compilation des assets
echo "⚙️ Compilation des assets Laravel ($MODE)..."
if [[ "$MODE" == "prod" ]]; then
  npm run production
else
  npm run dev
fi

# Lien symbolique
echo "🔗 Création du lien storage..."
php artisan storage:link

# Migration & seed
if [[ "$MODE" == "prod" ]]; then
  echo "🗄️  Migration base (force)..."
  php artisan migrate --force

  echo "🌱 Seeders de config (roles/permissions)..."
  php artisan db:seed --class=RolesTableSeeder --force
  php artisan db:seed --class=PermissionsTableSeeder --force
  php artisan db:seed --class=RoleHasPermissionsTableSeeder --force
else
  echo "🗄️  Migration + seed complet (dev)..."
  php artisan migrate:fresh --seed
fi

echo "✅ Setup Laravel terminé en mode '$MODE'."
