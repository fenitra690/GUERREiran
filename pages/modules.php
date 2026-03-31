<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h1>Test du Rewriting</h1>";

$id = isset($_GET['id']) ? $_GET['id'] : 'Non défini';
$idcat = isset($_GET['idcat']) ? $_GET['idcat'] : 'Non défini';

echo "<p>Paramètre <strong>id</strong> : " . htmlspecialchars($id) . "</p>";
echo "<p>Paramètre <strong>idcat</strong> : " . htmlspecialchars($idcat) . "</p>";
?>