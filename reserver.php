<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$file_path = __DIR__ . '/trips.json';

$trips = [];
if (file_exists($file_path)) {
    $json = file_get_contents($file_path);
    $trips = json_decode($json, true);
    if (!is_array($trips)) {
        $trips = [];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un Voyage</title>
    <link rel="stylesheet" href="my_trips.css">
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body>

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="presentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Nos voyages disponibles</h1>
        <p>Choisissez votre destination préférée</p>
    </div>
</header>

<section class="destinations" style="display: flex; flex-wrap: wrap;">
    <?php if (!empty($trips)): ?>
        <?php foreach ($trips as $trip): ?>
            <div class="destination-card">
                <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>">
                <h3><?= htmlspecialchars($trip['titre']) ?></h3>
                <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                <p><strong>Prix :</strong> <?= htmlspecialchars($trip['prix']) ?> €</p>
                <form action="paiement.php" method="POST">
                    <input type="hidden" name="trip_id" value="<?= htmlspecialchars($trip['id']) ?>">
                    <button type="submit" class="btn-primary">Réserver ce voyage</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun voyage disponible actuellement.</p>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
