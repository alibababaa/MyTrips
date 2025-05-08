<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie que l'ID du voyage est présent
if (!isset($_GET['trip_id'])) {
    die("Aucun identifiant de voyage fourni.");
}

$tripId = $_GET['trip_id'];

// Initialise le panier si vide
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Évite les doublons dans le panier
if (!in_array($tripId, $_SESSION['panier'])) {
    $_SESSION['panier'][] = $tripId;
}

// Redirige vers la page précédente ou vers mon_panier.php
header("Location: mon_panier.php");
exit();
