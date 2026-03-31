<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /rewriting3311/admin/login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

$stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BackOffice</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f4f6f8; color: #333; margin: 0; padding: 40px 20px;
        }
        .container {
            max-width: 1000px; margin: 0 auto; background: #fff; 
            padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 4px solid #333;
        }
        h1 { margin-top: 0; color: #111; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; font-size: 24px; }
        
        .admin-nav { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 25px; margin-top: 20px;
        }
        .btn { 
            text-decoration: none; padding: 10px 16px; background: #0056b3; 
            color: #fff; border-radius: 4px; font-weight: 500; font-size: 14px;
            transition: background 0.2s;
        }
        .btn:hover { background: #004494; }
        .btn-outline { background: #fff; border: 1px solid #ccc; color: #333; }
        .btn-outline:hover { background: #f9f9f9; }
        .btn-danger { background: #d32f2f; color: #fff; border: none; }
        .btn-danger:hover { background: #b71c1c; }
        .nav-links-right { display: flex; gap: 10px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f9f9f9; font-weight: 600; color: #555; text-transform: uppercase; font-size: 12px; }
        tr:hover { background: #fcfcfc; }
        
        .actions a { text-decoration: none; margin-right: 10px; font-weight: 500; }
        .view-link { color: #0056b3; }
        .view-link:hover { text-decoration: underline; }
        .edit-link { color: #f57c00; margin-left: 10px; }
        .edit-link:hover { text-decoration: underline; }
        .delete-link { color: #d32f2f; margin-left: 10px; }
        .delete-link:hover { text-decoration: underline; }
        
        .status-badge { 
            padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;
            background: #e6f4ea; color: #2e7d32; border: 1px solid #c8e6c9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Administrateur</h1>

        <div class="admin-nav">
            <a href="add_article.php" class="btn">+ Ajouter un nouvel article</a>
            <div class="nav-links-right">
                <a href="/rewriting3311/" target="_blank" class="btn btn-outline">Voir le site</a>
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre de l'article</th>
                    <th>Date de Création</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($articles as $a): ?>
                <tr>
                    <td>#<?php echo $a['id']; ?></td>
                    <td style="font-weight: 500; color: #111;"><?php echo htmlspecialchars($a['title']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($a['created_at'])); ?></td>
                    <td><span class="status-badge"><?php echo $a['is_published'] ? 'Publié' : 'Brouillon'; ?></span></td>
                    <td class="actions">
                        <a href="/rewriting3311/article/<?php echo $a['slug']; ?>-<?php echo $a['id']; ?>.html" target="_blank" class="view-link">Voir</a>
                        <a href="edit_article.php?id=<?php echo $a['id']; ?>" class="edit-link">Modifier</a>
                        <a href="delete_article.php?id=<?php echo $a['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');" class="delete-link">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

