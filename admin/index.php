<?php
// admin/index.php : Check session ou redirige
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /rewriting/admin/login.php");
    exit;
} else {
    header("Location: /rewriting/admin/dashboard.php");
    exit;
}
