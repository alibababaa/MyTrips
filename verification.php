<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = $_POST['card_number'] ?? '';
    $cardOwner  = $_POST['card_owner'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv        = $_POST['cvv'] ?? '';
    $tripId     = $_POST['trip_id'] ?? '';

    $isValid = preg_match('/^\d{16}$/', $cardNumber) &&
               preg_match('/^\d{2}\/\d{2}$/', $expiryDate) &&
               preg_match('/^\d{3}$/', $cvv) &&
               !empty($cardOwner) &&
               !empty($tripId);

    if ($isValid) {
        $params = [
            'trip_id' => $tripId,
            'prix_total' => $_POST['prix_total'] ?? 0,
            'nb_personnes' => $_POST['nb_personnes'] ?? 1,
        ];

        if (isset($_POST['options']) && is_array($_POST['options'])) {
            foreach ($_POST['options'] as $opt) {
                $params['options'][] = $opt;
            }
        }

        $query = http_build_query($params);
        header('Location: enregistrer_transaction.php?' . $query);
        exit;
    } else {
        header('Location: erreurpaiement.php');
        exit;
    }
} else {
    header('Location: accueil.php');
    exit();
}
