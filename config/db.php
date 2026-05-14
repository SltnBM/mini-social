<?php
$pdo = new PDO("mysql:host=localhost;dbname=db_name", "username", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>