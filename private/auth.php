<?php
// private/auth.php

require_once __DIR__ . '/db.php';

/**
 * Felhasználó regisztrációja.
 * Visszatérés:
 *  - ['success' => true] ha sikeres
 *  - ['success' => false, 'error' => 'üzenet'] ha hiba volt
 */
function registerUser(string $username, string $email, string $password): array
{
    // Trim alap biztonságból
    $username = trim($username);
    $email    = trim($email);

    if ($username === '' || $email === '' || $password === '') {
        return [
            'success' => false,
            'error'   => 'Minden mezőt ki kell tölteni.',
        ];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'error'   => 'Érvénytelen e-mail cím.',
        ];
    }

    if (strlen($password) < 6) {
        return [
            'success' => false,
            'error'   => 'A jelszó legyen legalább 6 karakter hosszú.',
        ];
    }

    // Ellenőrizzük, létezik-e már ilyen felhasználó
    $existingByUsername = getUserByUsernameOrEmail($username);
    if ($existingByUsername && $existingByUsername['username'] === $username) {
        return [
            'success' => false,
            'error'   => 'Ez a felhasználónév már foglalt.',
        ];
    }

    $existingByEmail = getUserByUsernameOrEmail($email);
    if ($existingByEmail && $existingByEmail['email'] === $email) {
        return [
            'success' => false,
            'error'   => 'Ez az e-mail cím már foglalt.',
        ];
    }

    // Jelszó hash-elése
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $ok = createUser($username, $email, $passwordHash);

    if (!$ok) {
        return [
            'success' => false,
            'error'   => 'Ismeretlen hiba történt a regisztráció során.',
        ];
    }

    return ['success' => true];
}

/**
 * Bejelentkezés felhasználónév VAGY e-mail + jelszóval.
 * Siker esetén beállítja a session-t és true-t ad vissza.
 */
function loginUser(string $identifier, string $password): bool
{
    $identifier = trim($identifier);

    if ($identifier === '' || $password === '') {
        return false;
    }

    $user = getUserByUsernameOrEmail($identifier);
    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }

    // Sikeres bejelentkezés – session változók beállítása
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['user_email'] = $user['email'];

    return true;
}

/**
 * Kijelentkezés – session törlése.
 */
function logoutUser(): void
{
    // Ürítsük ki a session tömböt
    $_SESSION = [];

    // Cookie érvénytelenítése (ha kell)
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

/**
 * true, ha a user be van jelentkezve.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Ha nincs belépve, átirányít a login oldalra.
 * Ezt pl. az index.php tetején lehet használni:
 *   require_once 'private/auth.php';
 *   requireLogin();
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Aktuális bejelentkezett user adatai.
 * Ha nincs bejelentkezve, null-t ad vissza.
 */
function getCurrentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    $id = (int) $_SESSION['user_id'];
    return getUserById($id);
}
