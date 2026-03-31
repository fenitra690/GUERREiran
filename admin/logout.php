<?php
// admin/logout.php
session_start();
session_destroy();
header("Location: /rewriting/admin/login.php");
exit;
