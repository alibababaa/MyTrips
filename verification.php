<?php
session_start();

// Simuler la vérification des coordonnées bancaires
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardNumber = $_POST['card_number'];
    $cardOwner = $_POST['card_owner'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];
    $tripId = $_POST['trip_id'];

    // Vérification de la validité des données
    if (preg_match('/^\d{16}$/', $cardNumber) && preg_match('/^\d{2}\/\d{2}$/', $expiryDate) && preg_match('/^\d{3}$/', $cvv)) {
        // Simuler une validation réussie
        header('Location: enregistrer_transaction.php?trip_id=' . $tripId);
        exit;
    } else {
        header('Location: erreur_paiement.php');
        exit;
    }
}
?>
