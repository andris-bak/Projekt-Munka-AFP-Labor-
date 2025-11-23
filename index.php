<?php
require_once __DIR__ . '/private/auth.php';
require_once __DIR__ . '/private/db.php';

// Ha nem lépett be → login oldal
requireLogin();

$user = getCurrentUser();

// Tranzakciók lekérése
$transactions = getTransactionsByUser($user['id']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pénzügyi Napló – Főoldal</title>
    <link rel="stylesheet" href="assets/common.css">
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>

    <header class="topbar">
        <h1>Pénzügyi Napló</h1>
        <div class="userinfo">
            <span>Bejelentkezve: <b><?= htmlspecialchars($user['username']) ?></b></span>
            <a class="logout-btn" href="logout.php">Kijelentkezés</a>
        </div>
    </header>

    <main class="content">

        <div class="actions">
            <a href="add_transaction.php" class="add-btn">+ Új tranzakció</a>
        </div>

        <h2>Tranzakciók</h2>

        <?php if (empty($transactions)): ?>
            <p>Még nincsenek tranzakciók. Add hozzá az elsőt!</p>
        <?php else: ?>

            <table class="tx-table">
                <thead>
                    <tr>
                        <th>Dátum</th>
                        <th>Leírás</th>
                        <th>Összeg</th>
                        <th>Kategória</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?= htmlspecialchars($tx['date']) ?></td>
                            <td><?= htmlspecialchars($tx['description']) ?></td>
                            <td><?= number_format($tx['amount'], 0, ',', ' ') ?> Ft</td>
                            <td><?= htmlspecialchars($tx['category'] ?? '–') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </main>

</body>
</html>
