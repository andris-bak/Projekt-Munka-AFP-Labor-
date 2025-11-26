<?php
require_once __DIR__ . '/private/auth.php';

logoutUser();

// sikeres kijelentkezés után irány a login oldal
header('Location: login.php');
exit;
