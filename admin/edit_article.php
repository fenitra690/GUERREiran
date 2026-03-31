<?php
// admin/edit_article.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /rewriting/admin/login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Récupérer l'article actuel
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die("Article introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $image_alt = trim($_POST['image_alt'] ?? '');
    
    // SEO slug
    $slug = slugify($title);
    
    $header_image_path = $article['header_image'];
    
    // Gérer l'upload d'image (si nouvelle image)
    if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/';
        $tmp_name = $_FILES['header_image']['tmp_name'];
        $name = basename($_FILES['header_image']['name']);
        
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $new_filename = uniqid('img_') . '.' . $ext;
        $dest = $upload_dir . $new_filename;
        
        $savedPath = compressImage($tmp_name, $dest, 20);
        $header_image_path = 'uploads/' . basename($savedPath);
    }
    
    if ($title && $content) {
        $update_stmt = $pdo->prepare("UPDATE articles SET title = ?, slug = ?, content = ?, header_image = ?, image_alt = ? WHERE id = ?");
        if ($update_stmt->execute([$title, $slug, $content, $header_image_path, $image_alt, $id])) {
            $success = "Article modifié avec succès.";
            $article['title'] = $title;
            $article['content'] = $content;
            $article['image_alt'] = $image_alt;
            
            // Purger le cache
            $pdo->query("DELETE FROM page_cache");
        } else {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Titre et contenu obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'article - BackOffice</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f4f6f8; color: #333; margin: 0; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #f57c00; }
        h2 { margin-top: 0; color: #111; font-size: 22px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
        .back-link { display: inline-block; margin-bottom: 25px; color: #555; text-decoration: none; font-weight: 500; font-size: 14px; }
        .back-link:hover { color: #111; text-decoration: underline; }
        .msg { padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .msg-error { background: #fdeaea; color: #d32f2f; border-left: 4px solid #d32f2f; }
        .msg-success { background: #fff3e0; color: #ef6c00; border-left: 4px solid #f57c00; }
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #444; }
        .form-group input[type="text"], .form-group textarea, .form-group input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; background: #fafafa; font-family: inherit; }
        .form-group input[type="text"]:focus, .form-group textarea:focus { border-color: #f57c00; outline: none; background: #fff; box-shadow: 0 0 0 3px rgba(245,124,0,0.1); }
        .form-group textarea { min-height: 200px; resize: vertical; line-height: 1.6; }
        .btn { padding: 12px 24px; background: #f57c00; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; transition: background 0.2s; }
        .btn:hover { background: #ef6c00; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier l'article #<?php echo $id; ?></h2>
        <a href="dashboard.php" class="back-link">&larr; Retour au Dashboard</a>
        
        <?php if($error): ?><div class="msg msg-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg msg-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre de l'article</label>
                <input type="text" name="title" id="title" required value="<?php echo htmlspecialchars($article['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="header_image">Nouvelle Image d'entête (Laissez vide pour conserver l'actuelle)</label>
                <?php if($article['header_image']): ?>
                    <p style="font-size:12px; color:#666;">Image actuelle : <?php echo htmlspecialchars($article['header_image']); ?></p>
                <?php endif; ?>
                <input type="file" name="header_image" id="header_image" accept="image/jpeg, image/png">
            </div>
            
            <div class="form-group">
                <label for="image_alt">Texte alternatif de l'image (SEO)</label>
                <input type="text" name="image_alt" id="image_alt" value="<?php echo htmlspecialchars($article['image_alt']); ?>">
            </div>
            
            <div class="form-group">
                <label for="content">Contenu HTML</label>
                <textarea name="content" id="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            
            <button type="submit" class="btn">Mettre à jour l'article</button>
        </form>
    </div>
</body>
</html>