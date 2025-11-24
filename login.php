<?php
require_once __DIR__ . '/private/auth.php';

// Ha be van jelentkezve, ne jelenjen meg a login oldal
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifier = $_POST['identifier'] ?? '';
    $password   = $_POST['password'] ?? '';

    if (loginUser($identifier, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Hibás felhasználónév/e-mail vagy jelszó.';
    }
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="assets/common.css">
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

    <div class="login-container">
        <h1>Pénzügyi Napló – Bejelentkezés</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>
                Felhasználónév vagy e-mail:
                <input type="text" name="identifier" required>
            </label>

            <label>
                Jelszó:
                <input type="password" name="password" required>
            </label>

            <button type="submit">Bejelentkezés</button>
        </form>

        <p>Még nincs fiókod?
            <a href="register.php">Regisztráció</a>
        </p>
    </div>

</body>
</html>


register:
<?php
require_once __DIR__ . '/private/auth.php';

// Ha már be van jelentkezve, ne lássa a regisztrációs oldalt
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $pass1    = $_POST['password'] ?? '';
    $pass2    = $_POST['password_confirm'] ?? '';

    if ($pass1 !== $pass2) {
        $error = 'A két jelszó nem egyezik.';
    } else {
        $result = registerUser($username, $email, $pass1);

        if ($result['success']) {
            $success = 'Sikeres regisztráció! Most már bejelentkezhetsz.';
        } else {
            $error = $result['error'] ?? 'Ismeretlen hiba történt.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="assets/common.css">
    <link rel="stylesheet" href="assets/register.css">
</head>
<body>

    <div class="register-container">
        <h1>Pénzügyi Napló – Regisztráció</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>
                Felhasználónév:
                <input type="text" name="username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
            </label>

            <label>
                E-mail:
                <input type="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </label>

            <label>
                Jelszó:
                <input type="password" name="password" required>
            </label>

            <label>
                Jelszó megerősítése:
                <input type="password" name="password_confirm" required>
            </label>

            <button type="submit">Regisztráció</button>
        </form>

        <p>Már van fiókod?
            <a href="login.php">Bejelentkezés</a>
        </p>
    </div>

</body>
</html>