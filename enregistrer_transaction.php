<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['trip_id'], $_GET['prix_total'])) {
    echo "Erreur : données manquantes.";
    exit();
}

$tripId = $_GET['trip_id'];
$prixTotal = (float) $_GET['prix_total'];
$nbPersonnes = isset($_GET['nb_personnes']) ? (int) $_GET['nb_personnes'] : 1;
$options = isset($_GET['options']) ? (array) $_GET['options'] : [];

$userLogin = $_SESSION['user']['login'];
$datePaiement = date('Y-m-d H:i:s');

$transactionsFile = __DIR__ . '/transactions.json';
$transactions = file_exists($transactionsFile)
    ? json_decode(file_get_contents($transactionsFile), true)
    : [];

if (!is_array($transactions)) {
    $transactions = [];
}

$transactions[] = [
    'user_id' => $userLogin,
    'trip_id' => $tripId,
    'payment_date' => $datePaiement,
    'montant' => $prixTotal,
    'nb_personnes' => $nbPersonnes,
    'options' => $options
];

file_put_contents($transactionsFile, json_encode($transactions, JSON_PRETTY_PRINT));
header('Location: confirmation.php');
exit();
