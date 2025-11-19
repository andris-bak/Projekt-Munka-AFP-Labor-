<?php
// private/db.php

require_once __DIR__ . '/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    // Globális PDO példány – egyszer jön létre, és mindenhol elérhető lesz.
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // dobjon kivételt
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // asszociatív tömbként adjon vissza mindent
        PDO::ATTR_EMULATE_PREPARES   => false,                  // natív prepared statement, ahol lehet
    ]);
} catch (PDOException $e) {
    // Fejlesztés alatt jó a konkrét hiba, élesben ezt szépíteni szoktuk.
    die('Adatbázis kapcsolat hiba: ' . $e->getMessage());
}
