<?php
session_start();

// Lecture du fichier trips.json
$tripsData = file_get_contents("trips.json");
$trips = json_decode($tripsData, true);


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
    <title>Réserver un Voyage</title>
    <link rel="stylesheet" href="my_trips.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Nos voyages disponibles</h1>
        <p>Choisissez votre destination préférée</p>
    </div>
</header>

<section class="destinations" style="display: flex;">
    <?php foreach ($trips as $index => $trip): ?>
        <div class="destination-card">
            <img src="images/voyage.jpg" alt="Voyage Image">
            <h3><?= htmlspecialchars($trip['title']) ?></h3>
            <p><strong>Dates :</strong> <?= htmlspecialchars($trip['dates']['start']) ?> → <?= htmlspecialchars($trip['dates']['end']) ?></p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($trip['price']) ?> €</p>
            <form action="trips_details.php" method="GET">
                <input type="hidden" name="id" value="<?= $index ?>">
                <button class="btn-primary" type="submit">Voir détails</button>
            </form>
        </div>
    <?php endforeach; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
