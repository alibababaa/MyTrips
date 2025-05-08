<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = $_POST['card_number'] ?? '';
    $cardOwner  = $_POST['card_owner'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv        = $_POST['cvv'] ?? '';
    $tripId     = $_POST['trip_id'] ?? '';

    // Vérifier les formats
    $isValid = preg_match('/^\d{16}$/', $cardNumber) &&
               preg_match('/^\d{2}\/\d{2}$/', $expiryDate) &&
               preg_match('/^\d{3}$/', $cvv) &&
               !empty($cardOwner) &&
               !empty($tripId);

    if ($isValid) {
        header('Location: enregistrer_transaction.php?trip_id=' . urlencode($tripId));
        exit;
    } else {
        header('Location: erreurpaiement.php'); // Corrigé le nom de la page
        exit;
    }
} else {
    // Si la page est accédée directement
    header('Location: accueil.php');
    exit;
}
