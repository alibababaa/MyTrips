<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}

$trips = json_decode(file_get_contents(__DIR__ . '/trips.json'), true);
$tripId = $_POST['trip_id'] ?? '';
$nbPersonnes = $_POST['nb_personnes'] ?? 1;
$prixTotal = $_POST['prix_total'] ?? 0;
$optionsChoisies = $_POST['options'] ?? [];

$selectedTrip = findTripById($trips, $tripId);

if (!$selectedTrip) {
    die("Voyage introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Paiement - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-paiement">
<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
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
        <h1>Paiement</h1>
        <p>Récapitulatif de votre voyage</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: auto;">
    <div style="border: 1px solid #ddd; padding: 1em; border-radius: 10px;">
        <h3><?= htmlspecialchars($selectedTrip['titre']) ?></h3>
        <p><strong>Durée :</strong> <?= htmlspecialchars($selectedTrip['duree']) ?> jours</p>
        <p><strong>Nombre de personnes :</strong> <?= htmlspecialchars($nbPersonnes) ?></p>
        <p><strong>Options choisies :</strong>
            <?= empty($optionsChoisies) ? 'Aucune' : implode(', ', array_map('htmlspecialchars', $optionsChoisies)) ?>
        </p>
        <p><strong>Prix total :</strong> <?= number_format($prixTotal, 2) ?> €</p>
    </div>
</section>

<h2 style="text-align: center;">Coordonnées de paiement</h2>
<form action="verification.php" method="POST" style="max-width: 600px; margin: 2em auto;">
    <label for="card_number">Numéro de carte (16 chiffres)</label>
    <input type="text" name="card_number" id="card_number" pattern="\d{16}" required placeholder="1234567812345678">

    <label for="card_owner">Nom et prénom du propriétaire</label>
    <input type="text" name="card_owner" id="card_owner" required placeholder="Jean Dupont">

    <label for="expiry_date">Date d'expiration (MM/AA)</label>
    <input type="text" name="expiry_date" id="expiry_date" pattern="\d{2}/\d{2}" required placeholder="MM/AA">

    <label for="cvv">Code de sécurité (CVV - 3 chiffres)</label>
    <input type="text" name="cvv" id="cvv" pattern="\d{3}" required placeholder="123">

    <!-- Données du voyage -->
    <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
    <input type="hidden" name="nb_personnes" value="<?= htmlspecialchars($nbPersonnes) ?>">
    <input type="hidden" name="prix_total" value="<?= htmlspecialchars($prixTotal) ?>">

    <?php foreach ($optionsChoisies as $opt): ?>
        <input type="hidden" name="options[]" value="<?= htmlspecialchars($opt) ?>">
    <?php endforeach; ?>

    <button type="submit" class="btn-primary">Payer</button>
</form>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
