<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trip_id'])) {
    $tripId = $_POST['trip_id'];

    if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
        // Supprimer le voyage du panier
        $_SESSION['panier'] = array_filter($_SESSION['panier'], function($id) use ($tripId) {
            return $id != $tripId;
        });
    }
}

// Redirige vers le panier
header("Location: mon_panier.php");
exit();
