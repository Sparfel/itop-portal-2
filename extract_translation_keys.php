<?php

// Fonction pour récupérer toutes les clés de traduction utilisées dans les fichiers Blade
function getTranslationKeys($directory)
{
    // Tableau pour stocker toutes les clés de traduction trouvées
    $translationKeys = [];

    // Vérification si le répertoire existe
    if (!is_dir($directory)) {
        echo "Erreur : Le répertoire spécifié n'existe pas: " . $directory . "\n";
        return [];
    }

    // Création d'un itérateur pour explorer les fichiers du répertoire
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    // Affichage du répertoire à analyser pour débogage
    echo "Exploration du répertoire: " . $directory . "\n";

    $foundFiles = false; // Flag pour vérifier si des fichiers Blade sont trouvés

    foreach ($iterator as $file) {
        // Affichage de chaque fichier ou répertoire analysé
        echo "En train de vérifier : " . $file->getRealPath() . "\n";

        // Vérifier que le fichier est un fichier normal et qu'il a l'extension .blade.php
        if ($file->isFile() && pathinfo($file->getRealPath(), PATHINFO_EXTENSION) === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
            $foundFiles = true; // On a trouvé au moins un fichier Blade
            // Affichage du fichier actuellement analysé
            echo "Fichier analysé : " . $file->getRealPath() . "\n";

            // Récupération du contenu du fichier
            $content = file_get_contents($file->getRealPath());

            // Expression régulière pour capturer les clés de traduction (__)
            preg_match_all('/\{\{\s*__\([\'"]([^\'"]+)[\'"]\)\s*\}\}/', $content, $matches);

            // Si des correspondances sont trouvées, on les ajoute au tableau des clés de traduction
            if (!empty($matches[1])) {
                echo "Clés trouvées dans " . $file->getRealPath() . ":\n";
                foreach ($matches[1] as $match) {
                    echo "  Clé trouvée: " . $match . "\n"; // Affiche la clé trouvée
                    // Ajouter la clé au tableau sans doublons
                    $translationKeys[$match] = true;
                }
            } else {
                // Si aucune clé n'est trouvée dans ce fichier, afficher un message
                echo "Aucune clé de traduction trouvée dans " . $file->getRealPath() . "\n";
            }
        }
    }

    if (!$foundFiles) {
        echo "Aucun fichier Blade trouvé dans le répertoire: " . $directory . "\n";
    }

    return array_keys($translationKeys);  // Retourner un tableau des clés trouvées
}

// Répertoire des fichiers Blade
$directory = __DIR__ . '/resources/views';  // Répertoire "resources/views"

// Vérification du répertoire
echo "Vérification du répertoire: " . $directory . "\n";

// Vérifier si le répertoire existe
if (!is_dir($directory)) {
    echo "Le répertoire spécifié n'existe pas: " . $directory . "\n";
    exit;
}

// Récupérer les clés de traduction
$keys = getTranslationKeys($directory);

// Affichage des clés trouvées
echo "\n--- Clés de traduction trouvées ---\n";
if (!empty($keys)) {
    foreach ($keys as $key) {
        echo $key . "\n";
    }
} else {
    echo "Aucune clé de traduction trouvée.\n";
}

// Optionnel: Sauvegarder dans un fichier
file_put_contents('translation_keys.txt', implode("\n", $keys));

echo "\nLes clés ont été sauvegardées dans le fichier 'translation_keys.txt'.\n";
?>
