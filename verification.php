<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Simuler la vérification des coordonnées bancaires
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardNumber = $_POST['card_number'] ?? '';
    $cardOwner  = $_POST['card_owner']  ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv        = $_POST['cvv']         ?? '';
    $tripId     = $_POST['trip_id']     ?? '';

    // Vérification de la validité des données
    $cardValid   = preg_match('/^\d{16}$/', $cardNumber);
    $expiryValid = preg_match('/^\d{2}\/\d{2}$/', $expiryDate);
    $cvvValid    = preg_match('/^\d{3}$/', $cvv);

    // Ici, on simule la validation (vous pourriez faire d'autres checks)
    if ($cardValid && $expiryValid && $cvvValid) {
        // Validation réussie
        header('Location: enregistrer.php?trip_id=' . urlencode($tripId));
        exit;
    } else {
        // Échec de la validation
        header('Location: erreurpaiement.php');
        exit;
    }
} else {
    echo "<p>Méthode de requête invalide.</p>";
}
