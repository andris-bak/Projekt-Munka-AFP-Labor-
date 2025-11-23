<?php

require_once __DIR__ . '/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Adatbázis kapcsolat hiba: ' . $e->getMessage());
}


function createUser(string $username, string $email, string $passwordHash): bool
{
    global $pdo;

    $sql = "INSERT INTO users (username, email, password_hash)
            VALUES (:username, :email, :password_hash)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':username'      => $username,
        ':email'         => $email,
        ':password_hash' => $passwordHash,
    ]);
}

function getUserById(int $id): ?array
{
    global $pdo;

    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $user = $stmt->fetch();
    return $user ?: null;
}

function getUserByUsernameOrEmail(string $identifier): ?array
{
    global $pdo;

    $sql = "SELECT * FROM users
            WHERE username = :username OR email = :email
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $identifier,
        ':email'    => $identifier,
    ]);

    $user = $stmt->fetch();
    return $user ?: null;
}

function createTransaction(
    int $userId,
    string $description,
    float $amount,
    string $date,
    ?string $category = null
): bool {
    global $pdo;

    $sql = "INSERT INTO transactions (user_id, description, amount, date, category)
            VALUES (:user_id, :description, :amount, :date, :category)";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':user_id'    => $userId,
        ':description'=> $description,
        ':amount'     => $amount,
        ':date'       => $date,
        ':category'   => $category,
    ]);
}

/**
 * Egy konkrét tranzakció lekérése ID + user alapján.
 * Ezzel biztosítjuk, hogy a user csak a sajátját lássa / módosítsa.
 */
function getTransactionById(int $txId, int $userId): ?array
{
    global $pdo;

    $sql = "SELECT * FROM transactions
            WHERE tx_id = :tx_id AND user_id = :user_id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tx_id'   => $txId,
        ':user_id' => $userId,
    ]);

    $tx = $stmt->fetch();
    return $tx ?: null;
}

/**
 * Tranzakciók listája egy userhez.
 * Opcionális dátumszűrés: $fromDate, $toDate (YYYY-MM-DD)
 */
function getTransactionsByUser(
    int $userId,
    ?string $fromDate = null,
    ?string $toDate = null
): array {
    global $pdo;

    $sql = "SELECT * FROM transactions
            WHERE user_id = :user_id";
    $params = [':user_id' => $userId];

    if ($fromDate !== null) {
        $sql .= " AND date >= :from_date";
        $params[':from_date'] = $fromDate;
    }

    if ($toDate !== null) {
        $sql .= " AND date <= :to_date";
        $params[':to_date'] = $toDate;
    }

    $sql .= " ORDER BY date DESC, created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

/**
 * Tranzakció törlése (csak a sajátját tudja törölni).
 */
function deleteTransaction(int $txId, int $userId): bool
{
    global $pdo;

    $sql = "DELETE FROM transactions
            WHERE tx_id = :tx_id AND user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tx_id'   => $txId,
        ':user_id' => $userId,
    ]);

    return $stmt->rowCount() > 0;
}

/**
 * Tranzakció kategóriájának frissítése.
 * Ez lesz az AI által javasolt kategória beírására is.
 */
function updateTransactionCategory(int $txId, int $userId, string $category): bool
{
    global $pdo;

    $sql = "UPDATE transactions
            SET category = :category
            WHERE tx_id = :tx_id AND user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':category' => $category,
        ':tx_id'    => $txId,
        ':user_id'  => $userId,
    ]);

    return $stmt->rowCount() > 0;
}

/**
 * Kategória szerinti összegzés (pl. kördiagramhoz).
 * Visszaadja: [ ['category' => 'Élelmiszer', 'total_amount' => 12345.67], ... ]
 */
function getCategorySummary(
    int $userId,
    ?string $fromDate = null,
    ?string $toDate = null
): array {
    global $pdo;

    $sql = "SELECT
                COALESCE(category, 'Egyéb') AS category,
                SUM(amount) AS total_amount
            FROM transactions
            WHERE user_id = :user_id";

    $params = [':user_id' => $userId];

    if ($fromDate !== null) {
        $sql .= " AND date >= :from_date";
        $params[':from_date'] = $fromDate;
    }

    if ($toDate !== null) {
        $sql .= " AND date <= :to_date";
        $params[':to_date'] = $toDate;
    }

    $sql .= " GROUP BY COALESCE(category, 'Egyéb')
              ORDER BY total_amount DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}
