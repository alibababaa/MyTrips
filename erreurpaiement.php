<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur de Paiement - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="mon_profil.php">Mon Profil</a></li>
            <li><a href="deconnexion.php">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="connexion.php">Connexion</a></li>
            <li><a href="inscription.php">Inscription</a></li>
        <?php endif; ?>
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                    style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Erreur de Paiement ❌</h1>
        <p>Les informations bancaires fournies sont incorrectes.</p>
    </div>
</header>

<section style="text-align: center; margin: 2em;">
    <p>Veuillez vérifier vos coordonnées et réessayer.</p>
    <a class="btn-primary" href="javascript:history.back()">← Retour au paiement</a>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
