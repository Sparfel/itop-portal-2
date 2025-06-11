#!/bin/bash

# Nettoyage des dossiers node_modules et des fichiers compilés
echo "Nettoyage des anciens dossiers node_modules et fichiers compilés..."
rm -rf node_modules
rm -rf resources/vendor/admin-lte/node_modules
rm -rf public/js public/css

# Installation des dépendances à la racine
echo "Installation des dépendances à la racine..."
npm install
echo "Exécution de npm audit fix à la racine..."
npm audit fix

# Installation des dépendances d'AdminLTE
echo "Installation des dépendances dans resources/vendor/admin-lte..."
cd resources/vendor/admin-lte
npm install
echo "Exécution de npm audit fix pour AdminLTE..."
npm audit fix

# Compilation des assets d'AdminLTE
echo "Compilation des assets AdminLTE (npm run production)..."
npm run production

# Retour à la racine et compilation globale
echo "Retour à la racine et compilation globale (npm run dev)..."
cd ../../..
npm run dev

echo "Installation et compilation terminées !"

