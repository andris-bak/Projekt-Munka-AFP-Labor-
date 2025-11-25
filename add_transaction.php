<?php
require_once __DIR__ . '/private/auth.php';
require_once __DIR__ . '/private/db.php';

requireLogin();

$user = getCurrentUser();

$error = '';

// alapértelmezett dátum: ma
$defaultDate = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = trim($_POST['description'] ?? '');
    $amountRaw   = trim($_POST['amount'] ?? '');
    $date        = trim($_POST['date'] ?? '');
    $category    = trim($_POST['category'] ?? '');

    // Alap ellenőrzések
    if ($description === '' || $amountRaw === '' || $date === '') {
        $error = 'Minden kötelező mezőt ki kell tölteni (leírás, összeg, dátum).';
    } else {
        // Összeg parse: vesszőt pontra cseréljük
        $amountNormalized = str_replace(',', '.', $amountRaw);

        if (!is_numeric($amountNormalized)) {
            $error = 'Az összegnek számnak kell lennie.';
        } else {
            $amount = (float)$amountNormalized;

            if ($amount <= 0) {
                $error = 'Az összeg legyen nagyobb, mint 0.';
            } else {
                // ha kategória üres string → legyen null
                if ($category === '') {
                    $categoryValue = null;
                } else {
                    $categoryValue = $category;
                }

                // Itt később jöhet majd az AI kategorizálás:
                // ha $categoryValue === null, akkor meghívunk egy AI függvényt,
                // pl. $categoryValue = aiCategorizeDescription($description);

                $ok = createTransaction(
                    $user['id'],
                    $description,
                    $amount,
                    $date,
                    $categoryValue
                );

                if ($ok) {
                    // Sikeres mentés után vissza az indexre
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Hiba történt a mentés során.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új tranzakció hozzáadása</title>
    <link rel="stylesheet" href="assets/common.css">
    <link rel="stylesheet" href="assets/add_transaction.css">
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
    <h2>Új tranzakció</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="tx-form">

        <label>
            Leírás*:
            <input type="text" name="description" required
                   value="<?= isset($description) ? htmlspecialchars($description) : '' ?>">
        </label>

        <label>
            Összeg (Ft)*:
            <input type="text" name="amount" required
                   placeholder="pl. 12500"
                   value="<?= isset($amountRaw) ? htmlspecialchars($amountRaw) : '' ?>">
        </label>

        <label>
            Dátum*:
            <input type="date" name="date" required
                   value="<?= isset($date) && $date !== '' ? htmlspecialchars($date) : $defaultDate ?>">
        </label>

        <label>
            Kategória (opcionális):
            <!-- itt most szabad szöveg, de lehetne <select> is a fix kategóriákkal -->
            <input type="text" name="category"
                   placeholder="pl. Élelmiszer, Autó, Szórakozás..."
                   value="<?= isset($category) ? htmlspecialchars($category) : '' ?>">
        </label>

        <div class="form-actions">
            <button type="submit">Mentés</button>
            <a href="index.php" class="cancel-link">Mégse</a>
        </div>
    </form>
</main>

</body>
</html>