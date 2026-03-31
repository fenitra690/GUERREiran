<?php
// init_db.php : Script à lancer une seule fois pour initialiser la DB
require_once 'includes/db.php';

echo "Initialisation de la base de données...<br>";

// Création de la table des utilisateurs
$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
)");

// Création de la table des articles
$pdo->exec("
CREATE TABLE IF NOT EXISTS articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    header_image VARCHAR(255),
    image_alt VARCHAR(255),
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_published BOOLEAN DEFAULT 1
)");

// Création de la table du cache HTML
$pdo->exec("
CREATE TABLE IF NOT EXISTS page_cache (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_url VARCHAR(255) UNIQUE NOT NULL,
    html_content TEXT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Insertion de l'utilisateur par défaut pour le professeur
$passwordHash = password_hash('prof', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT OR IGNORE INTO users (username, password) VALUES ('prof', ?)");
$stmt->execute([$passwordHash]);

// Insertion d'articles d'exemple
$article1 = [
    'title' => 'Guerre en Iran : Les dernières avancées sur le terrain, analyse de la situation',
    'slug' => 'guerre-en-iran-dernieres-avancees',
    'header_image' => '',
    'image_alt' => 'Troupes militaires au Moyen-Orient conflit Iran',
    'content' => '<h2>Le contexte géopolitique s\'envenime</h2>
                  <p>La <strong>guerre en Iran</strong> continue de secouer la région du Moyen-Orient, impactant directement les équilibres mondiaux. Selon les dernières sources diplomatiques, les tensions avec les pays frontaliers ont atteint un point critique ce week-end.</p>
                  <h3>L\'impact sur l\'économie mondiale</h3>
                  <p>Les prix du pétrole ont bondi de 15% suite aux escalades de ces derniers jours. Ce conflit aura des répercussions majeures et durables.</p>'
];
$stmt = $pdo->prepare("INSERT OR IGNORE INTO articles (title, slug, header_image, image_alt, content) VALUES (:title, :slug, :header_image, :image_alt, :content)");
$stmt->execute($article1);

$article2 = [
    'title' => 'Guerre en Iran : Pourparlers de paix à Genève, espoir ou illusion ?',
    'slug' => 'guerre-en-iran-pourparlers-geneve',
    'header_image' => '',
    'image_alt' => 'Négociations diplomatiques à Genève guerre Iran',
    'content' => '<h2>Tentative de résolution du conflit</h2>
                  <p>Les diplomates européens tentent une énième fois de trouver un accord pour freiner la <strong>guerre en Iran</strong>. La délégation internationale est arrivée à Genève avec un plan de désescalade stricte en plusieurs étapes.</p>
                  <h3>Les conditions du cessez-le-feu</h3>
                  <p>Parmi les éléments clés débattus : le retrait des forces des zones tampons et l\'ouverture de couloirs humanitaires.</p>'
];
$stmt->execute($article2);

echo "Base de données prête ! Les tables sont créées et un compte par défaut (user: prof, password: prof) a été ajouté.";
?>

