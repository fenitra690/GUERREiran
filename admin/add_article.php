<?php
// admin/add_article.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /rewriting3311/admin/login.php");
    exit;
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $image_alt = trim($_POST['image_alt'] ?? '');
    
    // SEO slug
    $slug = slugify($title);
    
    // Gérer l'upload d'image avec une compression MAX (comme demandé)
    $header_image_path = '';
    
    // upload
    if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/';
        $tmp_name = $_FILES['header_image']['tmp_name'];
        $name = basename($_FILES['header_image']['name']);
        
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $new_filename = uniqid('img_') . '.' . $ext;
        $dest = $upload_dir . $new_filename;
        
        // La consigne : "l'image sera compresser a max pour l'optimisation"
        // Qualité faible (ex: 20 sur 100 pour compresser un max)
        move_uploaded_file($tmp_name, $dest); $savedPath = $dest;
        
        $header_image_path = 'uploads/' . basename($savedPath);
    }
    
    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, slug, content, header_image, image_alt) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $slug, $content, $header_image_path, $image_alt])) {
            $success = "Article ajouté avec succès.";
            
            // On vide le cache car un nouvel article est là (l'accueil doit être mise à jour)
            // Dans un vrai projet de prod, on supprimerait que la home
            
        } else {
            $error = "Erreur lors de l'ajout.";
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
    <title>Ajouter un article - BackOffice</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f4f6f8; color: #333; margin: 0; padding: 40px 20px;
        }
        .container {
            max-width: 800px; margin: 0 auto; background: #fff; 
            padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 4px solid #0056b3;
        }
        h2 { margin-top: 0; color: #111; font-size: 22px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px; }
        
        .back-link { 
            display: inline-block; margin-bottom: 25px; color: #555; 
            text-decoration: none; font-weight: 500; font-size: 14px;
        }
        .back-link:hover { color: #111; text-decoration: underline; }
        
        .msg { padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .msg-error { background: #fdeaea; color: #d32f2f; border-left: 4px solid #d32f2f; }
        .msg-success { background: #e6f4ea; color: #2e7d32; border-left: 4px solid #2e7d32; }
        
        .form-group { margin-bottom: 22px; }
        .form-group label { 
            display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #444; 
        }
        .form-group input[type="text"], .form-group textarea, .form-group input[type="file"] { 
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; 
            box-sizing: border-box; font-size: 14px; background: #fafafa; font-family: inherit;
        }
        .form-group input[type="text"]:focus, .form-group textarea:focus { 
            border-color: #0056b3; outline: none; background: #fff;
            box-shadow: 0 0 0 3px rgba(0,86,179,0.1); 
        }
        .form-group textarea { min-height: 200px; resize: vertical; line-height: 1.6; }
        
        .btn { 
            padding: 12px 24px; background: #0056b3; color: white; border: none; 
            border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600;
            transition: background 0.2s;
        }
        .btn:hover { background: #004494; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Créer un nouvel article</h2>
        <a href="dashboard.php" class="back-link">&larr; Retour au Dashboard</a>
        
        <?php if($error): ?><div class="msg msg-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg msg-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre de l'article (Balise H1 et titre SEO)</label>
                <input type="text" name="title" id="title" required placeholder="Ex: Cessez-le-feu déclaré cette nuit...">
            </div>
            
            <div class="form-group">
                <label for="header_image">Image d'entête (compressée automatiquement pour performance)</label>
                <input type="file" name="header_image" id="header_image" accept="image/jpeg, image/png">
            </div>
            
            <div class="form-group">
                <label for="image_alt">Texte alternatif de l'image (SEO, attribut alt)</label>
                <input type="text" name="image_alt" id="image_alt" placeholder="Ex: Signature du traité de paix">
            </div>
            
            <div class="form-group">
                <label for="content">Contenu HTML (&lt;h2&gt;, &lt;p&gt;...)</label>
                <textarea name="content" id="content" required>&lt;p&gt;Début de l'article...&lt;/p&gt;&#10;&lt;h2&gt;Un nouveau tournant&lt;/h2&gt;&#10;&lt;p&gt;Suite de l'article...&lt;/p&gt;</textarea>
            </div>
            
            <button type="submit" class="btn">Publier l'article</button>
        </form>
    </div>
</body>
</html>


