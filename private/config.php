<?php
// private/config.php

// *** Adatbázis beállítások ***

define('DB_HOST', 'localhost');
define('DB_NAME', 'finance_diary');
define('DB_USER', 'root');
define('DB_PASS', '');

// Ha élesebb környezetre gondolsz a jövőben:
// - DB_USER / DB_PASS NE kerüljön be éles jelszóval a Git-be
// - Használhatsz .env fájlt vagy szerver oldali env változókat.

// *** PHP hibakijelzés fejlesztéshez ***
ini_set('display_errors', 1);
error_reporting(E_ALL);

// *** Session kezelés ***
// Ha ez a fájl minden oldalról be lesz húzva (pl. index.php elején require 'private/config.php';
// akkor elég itt egyszer elindítani a session-t.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
