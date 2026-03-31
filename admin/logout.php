<?php
// admin/logout.php
session_start();
session_destroy();
header("Location: /rewriting3311/admin/login.php");
exit;

