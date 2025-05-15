<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$trips = json_decode(file_get_contents(__DIR__ . '/trips.json'), true);

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}

$selectedTrips = [];
$prixTotal = 0;
$tripDetails = [];

// Paiement multiple (format trips[n][...])
if (isset($_POST['paiement_multiple']) && $_POST['paiement_multiple'] == '1' && isset($_POST['trips']) && is_array($_POST['trips'])) {
    foreach ($_POST['trips'] as $tripData) {
        $tripId = $tripData['trip_id'] ?? null;
        if (!$tripId) continue;

        $trip = findTripById($trips, $tripId);
        if (!$trip) continue;

        $nbPersonnes = intval($tripData['nb_personnes'] ?? 1);
        $prixTotalTrip = floatval($tripData['prix_total'] ?? ($trip['prix'] * $nbPersonnes));
        $options = $tripData['options'] ?? [];

        $selectedTrips[] = $trip;
        $prixTotal += $prixTotalTrip;

        $tripDetails[$tripId] = [
            'nb_personnes' => $nbPersonnes,
            'options' => $options,
            'prix_total' => $prixTotalTrip
        ];
    }
} else {
    // Paiement d’un seul voyage
    $tripId = $_POST['trip_id'] ?? '';
    $trip = findTripById($trips, $tripId);

    if (!$trip) {
        die("Voyage introuvable.");
    }

    $nbPersonnes = intval($_POST['nb_personnes'] ?? 1);
    $options = $_POST['options'] ?? [];
    $prixTotalTrip = floatval($_POST['prix_total'] ?? ($trip['prix'] * $nbPersonnes));

    $selectedTrips[] = $trip;
    $prixTotal = $prixTotalTrip;
    $tripDetails[$tripId] = [
        'nb_personnes' => $nbPersonnes,
        'options' => $options,
        'prix_total' => $prixTotalTrip
    ];
}

if (empty($selectedTrips)) {
    die("Aucun voyage valide à payer.");
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
            <button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Paiement</h1>
        <p>Récapitulatif de votre commande</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: auto;">
    <?php foreach ($selectedTrips as $trip): 
        $tripId = $trip['id'];
        $nb = $tripDetails[$tripId]['nb_personnes'];
        $opts = $tripDetails[$tripId]['options'];
        $total = $tripDetails[$tripId]['prix_total'];
    ?>
        <div style="border: 1px solid #ccc; padding: 1em; margin-bottom: 1em;">
            <h3><?= htmlspecialchars($trip['titre']) ?></h3>
            <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
            <p><strong>Nombre de personnes :</strong> <?= $nb ?></p>
            <p><strong>Options :</strong> 
                <?= empty($opts) ? 'Aucune' : implode(', ', array_map('htmlspecialchars', $opts)) ?>
            </p>
            <p><strong>Prix total :</strong> <?= number_format($total, 2) ?> €</p>
        </div>
    <?php endforeach; ?>
    <p style="text-align: right; font-size: 1.2em;"><strong>Total à payer :</strong> <?= number_format($prixTotal, 2) ?> €</p>
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

    <?php if (isset($_POST['paiement_multiple']) && $_POST['paiement_multiple'] == '1'): ?>
        <input type="hidden" name="paiement_multiple" value="1">
        <?php foreach ($tripDetails as $tripId => $infos): ?>
            <input type="hidden" name="trips[<?= $tripId ?>][trip_id]" value="<?= htmlspecialchars($tripId) ?>">
            <input type="hidden" name="trips[<?= $tripId ?>][nb_personnes]" value="<?= htmlspecialchars($infos['nb_personnes']) ?>">
            <input type="hidden" name="trips[<?= $tripId ?>][prix_total]" value="<?= htmlspecialchars($infos['prix_total']) ?>">
            <?php foreach ($infos['options'] as $opt): ?>
                <input type="hidden" name="trips[<?= $tripId ?>][options][]" value="<?= htmlspecialchars($opt) ?>">
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php $tripId = $selectedTrips[0]['id']; ?>
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
        <input type="hidden" name="nb_personnes" value="<?= htmlspecialchars($tripDetails[$tripId]['nb_personnes']) ?>">
        <input type="hidden" name="prix_total" value="<?= htmlspecialchars($tripDetails[$tripId]['prix_total']) ?>">
        <?php foreach ($tripDetails[$tripId]['options'] as $opt): ?>
            <input type="hidden" name="options[]" value="<?= htmlspecialchars($opt) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit" class="btn-primary">Payer</button>
</form>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
