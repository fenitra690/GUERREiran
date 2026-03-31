<?php
// admin/login.php
session_start();
require_once '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: /rewriting/admin/dashboard.php");
        exit;
    } else {
        $error = "Identifiants invalides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administration</title>
    <style>
        body { 
            margin: 0; padding: 0; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f8; 
            color: #333;
            display: flex; align-items: center; justify-content: center; height: 100vh;
        }
        .login-box {
            background: #ffffff;
            padding: 40px; border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 5px solid #0056b3; /* Ligne de contraste bleue stricte */
            width: 100%; max-width: 380px;
        }
        .login-box h2 {
            margin-top: 0; font-size: 1.5em; margin-bottom: 25px; 
            font-weight: 600; color: #111; text-align: center;
        }
        .error-msg {
            background: #fdeaea; color: #d32f2f; padding: 10px; 
            border-radius: 4px; margin-bottom: 20px; text-align: center; font-size: 0.9em; font-weight: 500;
        }
        .input-group { margin-bottom: 20px; }
        .input-group label {
            display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9em; color: #555;
        }
        .input-group input {
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;
            font-size: 1em; box-sizing: border-box; background: #fafafa;
        }
        .input-group input:focus {
            border-color: #0056b3; outline: none; background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
        }
        button {
            width: 100%; padding: 12px; background: #0056b3; color: white; border: none; 
            border-radius: 4px; cursor: pointer; font-size: 1em; font-weight: 600;
            transition: background 0.2s ease;
        }
        button:hover { background: #004494; }
        .back-btn { 
            display: block; text-align: center; margin-top: 20px; color: #666; 
            text-decoration: none; font-size: 0.9em; 
        }
        .back-btn:hover { color: #111; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Administration</h2>
        <?php if($error): ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required value="prof">
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required value="prof">
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <a href="/rewriting/" class="back-btn">&larr; Retour au site public</a>
    </div>
</body>
</html>
