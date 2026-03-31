<?php
// admin/delete_article.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /rewriting3311/admin/login.php");
    exit;
}

require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    
    // Purger le cache DB pour que la modif soit visible en front
    $pdo->query("DELETE FROM page_cache");
}

header("Location: dashboard.php");
exit;

