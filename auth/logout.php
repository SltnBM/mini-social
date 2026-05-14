<?php
session_start();
session_destroy();

header("Location: /mini-social/auth/login.php");
exit;
?>