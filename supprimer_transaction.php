<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$transactionsFile = __DIR__ . '/transactions.json';
$userLogin = $_SESSION['user']['login'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tripId = $_POST['trip_id'] ?? null;
    $paymentDate = $_POST['payment_date'] ?? null;

    if ($tripId && $paymentDate) {
        $transactions = json_decode(file_get_contents($transactionsFile), true) ?? [];
        $transactions = array_filter($transactions, function($t) use ($tripId, $paymentDate, $userLogin) {
            return !($t['trip_id'] == $tripId && $t['payment_date'] == $paymentDate && $t['user_id'] == $userLogin);
        });

        file_put_contents($transactionsFile, json_encode(array_values($transactions), JSON_PRETTY_PRINT));
    }
}

header('Location: historique.php');
exit();
