<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$reservations = json_decode(file_get_contents("reservations.json"), true);
$trips = json_decode(file_get_contents("trips.json"), true);
$userLogin = $_SESSION['user']['login'];

$userTrips = array_filter($reservations, function($res) use ($userLogin) {
    return $res['user'] === $userLogin;
});
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
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
        <p>Vos réservations passées</p>
    </div>
</header>

<section class="destinations" style="display: flex;">
    <?php foreach ($userTrips as $res): 
        $trip = $trips[(int)$res['trip_id']] ?? null;
        if ($trip): ?>
        <div class="destination-card">
            <h3><?= htmlspecialchars($trip['title']) ?></h3>
            <p><strong>Dates:</strong> <?= $trip['dates']['start'] ?> → <?= $trip['dates']['end'] ?></p>
            <p><strong>Prix:</strong> <?= $trip['price'] ?> €</p>
            <p><strong>Date réservation:</strong> <?= $res['date'] ?></p>
        </div>
    <?php endif; endforeach; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
