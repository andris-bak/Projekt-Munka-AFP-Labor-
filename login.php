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
