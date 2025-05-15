<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$login = $_SESSION['user']['login'] ?? '';
$usersFile = 'users.json';
$transactionsFile = 'transactions.json';

// Supprimer l'utilisateur
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$users = array_filter($users, fn($u) => $u['login'] !== $login);
file_put_contents($usersFile, json_encode(array_values($users), JSON_PRETTY_PRINT));

// Supprimer ses réservations (facultatif mais propre)
$transactions = file_exists($transactionsFile) ? json_decode(file_get_contents($transactionsFile), true) : [];
$transactions = array_filter($transactions, fn($t) => $t['user_id'] !== $login);
file_put_contents($transactionsFile, json_encode(array_values($transactions), JSON_PRETTY_PRINT));

// Déconnexion
session_unset();
session_destroy();

header('Location: accueil.php');
exit;
?>
