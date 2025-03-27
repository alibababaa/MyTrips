<?php
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
    $transactions = json_decode(file_get_contents('../data/transactions.json'), true) ?? [];
    $transactions[] = $paymentDetails;
    file_put_contents('../data/transactions.json', json_encode($transactions, JSON_PRETTY_PRINT));

    echo "<p>Paiement réussi et transaction enregistrée.</p>";
} else {
    echo "<p>Erreur lors de l'enregistrement du paiement.</p>";
}
?>
