<?php
session_start();

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie que l'ID du voyage est passé
if (!isset($_GET['trip_id'])) {
    echo "Erreur : Aucun identifiant de voyage fourni.";
    exit();
}

$tripId = $_GET['trip_id'];
$userLogin = $_SESSION['user']['login'];
$datePaiement = date('Y-m-d H:i:s');

// Chargement des transactions existantes
$transactionsFile = __DIR__ . '/transactions.json';
$transactions = file_exists($transactionsFile)
    ? json_decode(file_get_contents($transactionsFile), true)
    : [];

if (!is_array($transactions)) {
    $transactions = [];
}

// Ajout de la transaction
$transactions[] = [
    'user_id' => $userLogin,
    'trip_id' => $tripId,
    'payment_date' => $datePaiement
];

// Sauvegarde dans le fichier
file_put_contents($transactionsFile, json_encode($transactions, JSON_PRETTY_PRINT));

// Redirection vers la confirmation
header('Location: confirmation.php');
exit();
