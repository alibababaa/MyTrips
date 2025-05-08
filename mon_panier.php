<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$tripsFile = __DIR__ . '/trips.json';
$trips = file_exists($tripsFile) ? json_decode(file_get_contents($tripsFile), true) : [];

$panier = $_SESSION['panier'] ?? [];

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
    <title>Mon Panier - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-panier">

<nav>
    <div class="logo"><img src="logo_my_trips.png" alt="Logo My Trips"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="reserver.php">Réserver</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a class="active" href="mon_panier.php">Mon Panier</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                    style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Mon Panier</h1>
        <p>Liste des voyages sélectionnés</p>
    </div>
</header>

<section class="destinations" style="display: flex; flex-wrap: wrap;">
    <?php if (!empty($panier)): ?>
        <?php foreach ($panier as $tripId):
            $trip = findTripById($trips, $tripId);
            if ($trip): ?>
                <div class="destination-card">
                    <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>">
                    <h3><?= htmlspecialchars($trip['titre']) ?></h3>
                    <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($trip['prix']) ?> €</p>
                    
                    <form action="supprimer_du_panier.php" method="POST">
                        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
                        <button type="submit" class="btn-primary" style="background-color: #c0392b;">Supprimer ❌</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div style="width: 100%; text-align: center; margin-top: 2em;">
            <form action="paiement.php" method="POST">
                <?php foreach ($panier as $id): ?>
                    <input type="hidden" name="trip_ids[]" value="<?= htmlspecialchars($id) ?>">
                <?php endforeach; ?>
                <button type="submit" class="btn-primary">Passer au paiement 💳</button>
            </form>
        </div>
    <?php else: ?>
        <p style="text-align: center;">Votre panier est vide.</p>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
