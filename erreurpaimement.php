<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Ici, on affiche simplement un message d'erreur
echo "<p>Erreur lors de la vérification du paiement. Les coordonnées bancaires sont invalides ou fonds insuffisants.</p>";
echo '<p><a href="paiement.php">Revenir au paiement</a></p>';
echo '<p><a href="reserver.php">Retour à la page de réservation</a></p>';
