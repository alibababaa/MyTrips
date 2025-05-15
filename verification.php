<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = $_POST['card_number'] ?? '';
    $cardOwner  = $_POST['card_owner'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv        = $_POST['cvv'] ?? '';

    // Vérification des champs
    $isValid = preg_match('/^\d{16}$/', $cardNumber) &&
               preg_match('/^\d{2}\/\d{2}$/', $expiryDate) &&
               preg_match('/^\d{3}$/', $cvv) &&
               !empty($cardOwner);

    if (!$isValid) {
        header('Location: erreurpaiement.php');
        exit();
    }

    $paiementData = [];

    // Paiement multiple via trips[n][...]
    if (isset($_POST['paiement_multiple']) && $_POST['paiement_multiple'] == '1' && isset($_POST['trips']) && is_array($_POST['trips'])) {
        $paiementData['multiple'] = true;
        $paiementData['trips'] = [];

        foreach ($_POST['trips'] as $tripInfo) {
            $tripId = $tripInfo['trip_id'] ?? null;
            $nb = $tripInfo['nb_personnes'] ?? 1;
            $prix = $tripInfo['prix_total'] ?? 0;
            $options = $tripInfo['options'] ?? [];

            if (!$tripId) continue;
            if (!is_array($options)) $options = [];

            $paiementData['trips'][] = [
                'trip_id' => $tripId,
                'nb_personnes' => (int)$nb,
                'prix_total' => $prix,
                'options' => $options
            ];
        }
    }
    // Paiement simple
    else {
        $tripId = $_POST['trip_id'] ?? '';
        $nb = $_POST['nb_personnes'] ?? 1;
        $prix = $_POST['prix_total'] ?? 0;
        $options = $_POST['options'] ?? [];

        if (!is_array($options)) $options = [];

        $paiementData = [
            'multiple' => false,
            'trip_id' => $tripId,
            'nb_personnes' => (int)$nb,
            'prix_total' => $prix,
            'options' => $options
        ];
    }

    // Enregistrement en session
    $_SESSION['paiement'] = $paiementData;

    // Redirection
    header('Location: enregistrer_transaction.php');
    exit();
} else {
    header('Location: accueil.php');
    exit();
}
