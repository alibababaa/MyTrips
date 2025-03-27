<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Enregistrement de la transaction
if (isset($_GET['trip_id'])) {
    $tripId = $_GET['trip_id'];
    $userId = $_SESSION['user']['login'];
    $paymentDetails = [
        'user_id' => $userId,
        'trip_id' => $tripId,
        'payment_date' => date('Y-m-d H:i:s'),
    ];

    // Sauvegarde dans un fichier JSON (ou base de données)
    $file = __DIR__ . '/../data/transactions.json';
    $transactions = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $transactions[] = $paymentDetails;
    file_put_contents($file, json_encode($transactions, JSON_PRETTY_PRINT));

    echo "<p>Paiement réussi et transaction enregistrée.</p>";
    echo '<p><a href="mon_profil.php">Revenir à mon profil</a></p>';
} else {
    echo "<p>Erreur lors de l'enregistrement du paiement.</p>";
    echo '<p><a href="accueil.php">Retour à l\'accueil</a></p>';
}
