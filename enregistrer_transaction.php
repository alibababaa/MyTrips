<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['paiement'])) {
    header('Location: accueil.php');
    exit();
}

$userLogin = $_SESSION['user']['login'];
$paiement = $_SESSION['paiement'];

$transactionsFile = __DIR__ . '/transactions.json';
$transactions = file_exists($transactionsFile) ? json_decode(file_get_contents($transactionsFile), true) : [];

$tripsFile = __DIR__ . '/trips.json';
$trips = file_exists($tripsFile) ? json_decode(file_get_contents($tripsFile), true) : [];

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}

$datePaiement = date('Y-m-d H:i:s');
$tripsPayés = [];

// Paiement multiple
if (!empty($paiement['multiple']) && !empty($paiement['trips'])) {
    foreach ($paiement['trips'] as $tripInfo) {
        $trip = findTripById($trips, $tripInfo['trip_id']);
        if (!$trip) continue;

        $nbPersonnes = (int)($tripInfo['nb_personnes'] ?? 1);
        $options = $tripInfo['options'] ?? [];
        $montant = $trip['prix'] + count($options) * 20;
        $montant *= $nbPersonnes;

        $transactions[] = [
            'user_id' => $userLogin,
            'trip_id' => $trip['id'],
            'nb_personnes' => $nbPersonnes,
            'options' => $options,
            'montant' => $montant,
            'payment_date' => $datePaiement
        ];

        $tripsPayés[] = $trip['id'];
    }
}
// Paiement simple
else {
    $trip = findTripById($trips, $paiement['trip_id']);
    if ($trip) {
        $nbPersonnes = (int)($paiement['nb_personnes'] ?? 1);
        $options = $paiement['options'] ?? [];
        $montant = $trip['prix'] + count($options) * 20;
        $montant *= $nbPersonnes;

        $transactions[] = [
            'user_id' => $userLogin,
            'trip_id' => $trip['id'],
            'nb_personnes' => $nbPersonnes,
            'options' => $options,
            'montant' => $montant,
            'payment_date' => $datePaiement
        ];

        $tripsPayés[] = $trip['id'];
    }
}

// Mise à jour du panier : retirer les voyages payés
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = array_filter($_SESSION['panier'], function($item) use ($tripsPayés) {
        return !(isset($item['trip_id']) && in_array($item['trip_id'], $tripsPayés));
    });
    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexe proprement
}

// Enregistre les transactions
file_put_contents($transactionsFile, json_encode($transactions, JSON_PRETTY_PRINT));

// Nettoie les données de paiement
unset($_SESSION['paiement']);

// Redirection vers confirmation
header('Location: confirmation.php');
exit();
