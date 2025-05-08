<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                    style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">
                🌓
            </button>
        </li>
    </ul>
</nav>

<!-- Bannière -->
<header class="banner">
    <div class="banner-content">
        <h1>Merci pour votre réservation !</h1>
        <p>Votre paiement a bien été pris en compte.</p>
    </div>
</header>

<!-- Section de confirmation -->
<section style="text-align: center; padding: 2em;">
    <h2>🎉 Réservation Confirmée</h2>
    <p>Votre réservation a été enregistrée avec succès.</p>
    <a href="mon_profil.php" class="btn-primary">Voir mes réservations</a>
</section>

<!-- Footer -->
<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
