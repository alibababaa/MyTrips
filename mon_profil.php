<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Chargement sécurisé des fichiers JSON
$reservationsPath = __DIR__ . '/transactions.json';
$tripsPath = __DIR__ . '/trips.json';

$reservations = file_exists($reservationsPath) ? json_decode(file_get_contents($reservationsPath), true) : [];
$trips = file_exists($tripsPath) ? json_decode(file_get_contents($tripsPath), true) : [];

$userLogin = $_SESSION['user']['login'];

// Filtrer les réservations de l'utilisateur actuel
$userTrips = array_filter($reservations, function($res) use ($userLogin) {
    return $res['user_id'] === $userLogin;
});

// Fonction pour retrouver les infos du voyage par ID
function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) {
            return $trip;
        }
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="my_trips.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['login']) ?></h1>
        <p>Vos réservations passées</p>
    </div>
</header>

<section class="destinations" style="display: flex; flex-wrap: wrap;">
    <?php if (!empty($userTrips)): ?>
        <?php foreach ($userTrips as $res): 
            $trip = findTripById($trips, $res['trip_id']);
            if ($trip): ?>
                <div class="destination-card">
                    <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>">
                    <h3><?= htmlspecialchars($trip['titre']) ?></h3>
                    <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($trip['prix']) ?> €</p>
                    <p><strong>Date de réservation :</strong> <?= htmlspecialchars($res['payment_date']) ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Vous n'avez aucune réservation pour le moment.</p>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
