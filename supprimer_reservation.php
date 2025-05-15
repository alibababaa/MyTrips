<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$userLogin = $_SESSION['user']['login'];
$tripId = $_POST['trip_id'] ?? null;
$paymentDate = $_POST['payment_date'] ?? null;

if (!$tripId || !$paymentDate) {
    header('Location: mon_profil.php?error=missing_data');
    exit();
}

$transactionsFile = __DIR__ . '/transactions.json';
$transactions = file_exists($transactionsFile) ? json_decode(file_get_contents($transactionsFile), true) : [];

$transactions = array_filter($transactions, function($t) use ($userLogin, $tripId, $paymentDate) {
    return !(
        $t['user_id'] === $userLogin &&
        $t['trip_id'] == $tripId &&
        $t['payment_date'] === $paymentDate
    );
});

file_put_contents($transactionsFile, json_encode(array_values($transactions), JSON_PRETTY_PRINT));
header('Location: mon_profil.php?deleted=1');
exit();
