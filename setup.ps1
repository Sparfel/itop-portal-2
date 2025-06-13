# setup.ps1

Write-Host "Nettoyage des anciens dossiers node_modules et fichiers compilés..."
Remove-Item -Recurse -Force "node_modules" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "resources/vendor/admin-lte/node_modules" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "public/js", "public/css" -ErrorAction SilentlyContinue

Write-Host "Installation des dépendances à la racine..."
npm install
Write-Host "Exécution de npm audit fix à la racine..."
npm audit fix

Write-Host "Installation des dépendances dans resources/vendor/admin-lte..."
Set-Location "resources/vendor/admin-lte"
npm install
Write-Host "Exécution de npm audit fix pour AdminLTE..."
npm audit fix

Write-Host "Compilation des assets AdminLTE (npm run production)..."
npm run production

Write-Host "Retour à la racine du projet..."
Set-Location "..\..\.."

Write-Host "Compilation globale (npm run dev)..."
npm run dev

Write-Host "Création du lien symbolique Laravel (php artisan storage:link)..."
php artisan storage:link

Write-Host "✅ Installation et compilation terminées !"
