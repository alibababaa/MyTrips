<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$reservationsPath = __DIR__ . '/transactions.json';
$tripsPath = __DIR__ . '/trips.json';

$reservations = file_exists($reservationsPath) ? json_decode(file_get_contents($reservationsPath), true) : [];
$trips = file_exists($tripsPath) ? json_decode(file_get_contents($tripsPath), true) : [];

$userLogin = $_SESSION['user']['login'] ?? '';

$userTrips = array_filter($reservations, fn($res) => $res['user_id'] === $userLogin);
usort($userTrips, fn($a, $b) => strtotime($b['payment_date']) <=> strtotime($a['payment_date']));

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-profil">

<nav>
    <div class="logo"><img src="logo_my_trips.png" alt="Logo My Trips"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a class="active" href="mon_profil.php">Mon Profil</a></li>
        <li><a href="mon_panier.php">Mon Panier</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['login']) ?></h1>
        <p>Voici l’historique de vos réservations</p>
    </div>
</header>

<section class="destinations" style="display: flex; flex-wrap: wrap; justify-content: center;">
    <?php if (!empty($userTrips)): ?>
        <?php foreach ($userTrips as $res):
            $trip = findTripById($trips, $res['trip_id']);
            if ($trip): ?>
                <div class="destination-card">
                    <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>">
                    <h3><?= htmlspecialchars($trip['titre']) ?></h3>
                    <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($trip['prix']) ?> €</p>
                    <p><strong>Réservé le :</strong> <?= date('d/m/Y H:i', strtotime($res['payment_date'] ?? '')) ?></p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center;">Vous n'avez encore réservé aucun voyage.</p>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
