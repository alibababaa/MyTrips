<?php
session_start();

// Redirige vers la connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Chargement sécurisé des données depuis trips.json
$tripsData = file_get_contents("../trips.json");
$trips = json_decode($trips, true);

// Sélection du voyage depuis l'ID passé en paramètre
$selectedTrip = null;
if (isset($_GET['trip_id'])) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $_GET['trip_id']) {
            $selectedTrip = $trip;
            break;
        }
    }
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

<section class="destinations" style="display: flex; flex-wrap: wrap;">
   <?php if (is_array($trips) && !empty($trips)): ?>
    <?php foreach ($trips as $trip): ?>
        <div class="destination-card">
            <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>">
            <h3><?= htmlspecialchars($trip['titre']) ?></h3>
            <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($trip['prix']) ?> €</p>
            <form action="reserver.php" method="GET">
                <input type="hidden" name="trip_id" value="<?= htmlspecialchars($trip['id']) ?>">
                <button class="btn-primary" type="submit">Réserver ce voyage</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun voyage disponible.</p>
<?php endif; ?>

</section>

<?php if ($selectedTrip): ?>
    <section class="selected-trip">
        <h2><?= htmlspecialchars($selectedTrip['titre']) ?></h2>
        <img src="<?= htmlspecialchars($selectedTrip['image']) ?>" alt="<?= htmlspecialchars($selectedTrip['titre']) ?>">
        <p><strong>Prix :</strong> <?= htmlspecialchars($selectedTrip['prix']) ?> €</p>
        <p><strong>Durée :</strong> <?= htmlspecialchars($selectedTrip['duree']) ?> jours</p>

        <form method="POST">
            <input type="hidden" name="trip_id" value="<?= htmlspecialchars($selectedTrip['id']) ?>">
            <button type="submit" class="btn-primary">Confirmer la réservation</button>
        </form>
    </section>
<?php endif; ?>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
