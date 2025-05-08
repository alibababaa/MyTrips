<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Charger les voyages
function loadTrips() {
    $file = __DIR__ . '/trips.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

if (!isset($_GET['trip_id'])) {
    die("Aucun voyage sélectionné.");
}

$tripId = $_GET['trip_id'];
$trips = loadTrips();

$tripDetails = null;
foreach ($trips as $trip) {
    if (isset($trip['id']) && $trip['id'] == $tripId) {
        $tripDetails = $trip;
        break;
    }
}

if (!$tripDetails) {
    die("Voyage introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Détails du voyage - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body>

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Détails du voyage</h1>
        <p>Découvrez toutes les informations sur ce circuit</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: 2em auto; text-align: center;">
    <h2><?= htmlspecialchars($tripDetails['titre']) ?></h2>

    <?php if (!empty($tripDetails['image'])): ?>
        <img src="<?= htmlspecialchars($tripDetails['image']) ?>" alt="Image du voyage" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 1em;">
    <?php endif; ?>

    <p><strong>Prix :</strong> <?= htmlspecialchars($tripDetails['prix']) ?> €</p>
    <p><strong>Durée :</strong> <?= htmlspecialchars($tripDetails['duree']) ?> jours</p>

    <?php if (!empty($tripDetails['etapes']) && is_array($tripDetails['etapes'])): ?>
        <p><strong>Étapes :</strong> <?= htmlspecialchars(implode(', ', $tripDetails['etapes'])) ?></p>
    <?php endif; ?>

    <form action="paiement.php" method="POST">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripDetails['id']) ?>">
        <button class="btn-primary" type="submit">Réserver ce voyage</button>
    </form>

    <p style="margin-top: 1.5em;"><a href="reserver.php">← Retour aux voyages</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
