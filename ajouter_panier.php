<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie que l'ID du voyage est fourni
if (!isset($_POST['trip_id'])) {
    die("Aucun identifiant de voyage fourni.");
}

$tripId = $_POST['trip_id'];
$nbPersonnes = isset($_POST['nb_personnes']) ? max(1, (int)$_POST['nb_personnes']) : 1;
$options = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : [];
$prixEstime = isset($_POST['prix_estime']) ? floatval($_POST['prix_estime']) : 0.0;

// Initialise le panier s'il n'existe pas
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Vérifie si un élément similaire est déjà présent (même trip_id, nb_personnes et options)
$dejaPresent = false;
foreach ($_SESSION['panier'] as $item) {
    if (
        isset($item['trip_id'], $item['nb_personnes'], $item['options']) &&
        $item['trip_id'] == $tripId &&
        $item['nb_personnes'] == $nbPersonnes &&
        $item['options'] === $options
    ) {
        $dejaPresent = true;
        break;
    }
}

// Ajoute au panier si ce n'est pas un doublon exact
if (!$dejaPresent) {
    $_SESSION['panier'][] = [
        'trip_id' => $tripId,
        'nb_personnes' => $nbPersonnes,
        'options' => $options,
        'prix_total' => $prixEstime
    ];
}

// Redirige vers le panier
header("Location: mon_panier.php");
exit();
