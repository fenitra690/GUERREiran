<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/db.php';

try {
    // Vider la table
    $pdo->exec("DELETE FROM page_cache");
    echo "<h1>Le cache de la base de données a été purgé avec succès !</h1>";
    echo "<p><a href='/rewriting/'>Cliquez ici pour retourner à l'accueil du site</a> et voir les changements (<strong>n'oubliez pas de faire Ctrl + F5</strong> pour vider aussi le cache de votre navigateur).</p>";
} catch(Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
