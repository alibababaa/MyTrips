<?php
session_start();

 $file_path = '/Users/ilyesfellah/Downloads/MyTrips-main-15/trips.json';


// Vérifie si le fichier existe et peut être lu
if (file_exists($file_path) && is_readable($file_path)) {
    $json_content = file_get_contents($file_path);
    
    // Vérifie si le contenu du fichier a été correctement lu
    if ($json_content !== false) {
        $trips = json_decode($json_content, true);

        // Vérifie si le décodage JSON a réussi
        if ($trips === null && json_last_error() !== JSON_ERROR_NONE) {
            echo "Erreur de décodage JSON: " . json_last_error_msg();
        }
    } else {
        echo "Erreur lors de la lecture du fichier JSON.";
    }
} else {
    echo "Le fichier trips.json est introuvable ou inaccessible.";
}

// Vérifie si la variable $trips est bien définie avant de l'utiliser
if (isset($trips) && is_array($trips)) {
    // Code pour afficher les voyages
} else {
    echo "Aucun voyage disponible.";
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
