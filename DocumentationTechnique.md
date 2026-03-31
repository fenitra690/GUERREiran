# Document Technique - Projet InfoLibre (Guerre en Iran)
# Choix de technologie PHP et SQLite pour rapiditer et efficaciter projet leger

## 👥 Informations Étudiants
- **Étudiant 1 :** ETU003239
- **Étudiant 2 :** ETU003311

---

## 🔗 Liens du Projet

- **Lancement via Docker :** `docker compose up -d --build` (Accessible sur `http://localhost:8080/rewriting/`)

---

## 🗄️ Modélisation de la Base de Données (SQLite)

```text
+---------------------+       +------------------------+       +------------------------+
|        users        |       |        articles        |       |       page_cache       |
+---------------------+       +------------------------+       +------------------------+
| PK id (INTEGER)     |       | PK id (INTEGER)        |       | PK id (INTEGER)        |
| username (VARCHAR)  |       | title (VARCHAR)        |       | page_url (VARCHAR)     |
| password (VARCHAR)  |       | slug (VARCHAR)         |       | html_content (TEXT)    |
| role (VARCHAR)      |       | header_image (VARCHAR) |       | last_updated (DATETIME)|
+---------------------+       | image_alt (VARCHAR)    |       +------------------------+
                              | content (TEXT)         |
                              | created_at (DATETIME)  |
                              | is_published (BOOLEAN) |
                              +------------------------+
```

---

## 🔐 BackOffice (Administration)

- **URL :** `http://localhost:8080/rewriting/admin/login.php`
- **User par défaut :** `prof`
- **Mot de passe :** `prof`

## comment le lancer
docker compose up -d --build

## port
 http://localhost:8080/rewriting/

