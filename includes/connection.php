<?php
include_once "constants.php";

try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
    echo "Oh no! This will set you back a few seconds... " . $e->getMessage();
}