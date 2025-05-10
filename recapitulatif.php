<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

function loadTrips() {
    $file = __DIR__ . '/trips.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

if (!isset($_POST['trip_id'], $_POST['nb_personnes'])) {
    die("Données incomplètes.");
}

$tripId = $_POST['trip_id'];
$nbPersonnes = max(1, (int) $_POST['nb_personnes']);
$etapesChoisies = isset($_POST['etapes']) && is_array($_POST['etapes']) ? array_map('htmlspecialchars', $_POST['etapes']) : [];
$optionsChoisies = isset($_POST['options']) && is_array($_POST['options']) ? array_map('htmlspecialchars', $_POST['options']) : [];

$trips = loadTrips();
$tripDetails = null;

foreach ($trips as $trip) {
    if ($trip['id'] == $tripId) {
        $tripDetails = $trip;
        break;
    }
}

if (!$tripDetails) {
    die("Voyage introuvable.");
}

$prixBase = (int) $tripDetails['prix'];
$prixParEtape = 10;
$prixTotal = $prixBase * $nbPersonnes + count($etapesChoisies) * $prixParEtape;

// Calcul du coût des options
foreach ($optionsChoisies as $option) {
    switch ($option) {
        case 'assurance':
            $prixTotal += 20 * $nbPersonnes;
            break;
        case 'bagage':
            $prixTotal += 30 * $nbPersonnes;
            break;
        case 'guide':
            $prixTotal += 50;
            break;
        case 'transport':
            $prixTotal += 100;
            break;
        case 'premium':
            $prixTotal += 40 * $nbPersonnes;
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Récapitulatif - My Trips</title>
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
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Récapitulatif du voyage</h1>
        <p>Vérifiez les informations avant de procéder au paiement</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: 2em auto;">
    <h2><?= htmlspecialchars($tripDetails['titre']) ?></h2>

    <p><strong>Nombre de personnes :</strong> <?= htmlspecialchars($nbPersonnes) ?></p>

    <p><strong>Options choisies :</strong>
        <?= empty($optionsChoisies) ? 'Aucune' : implode(', ', $optionsChoisies) ?>
    </p>

    <p><strong>Prix total :</strong> <?= $prixTotal ?> €</p>

    <form action="paiement.php" method="POST">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
        <input type="hidden" name="nb_personnes" value="<?= htmlspecialchars($nbPersonnes) ?>">
        <input type="hidden" name="prix_total" value="<?= htmlspecialchars($prixTotal) ?>">

        <?php foreach ($optionsChoisies as $option): ?>
            <input type="hidden" name="options[]" value="<?= $option ?>">
        <?php endforeach; ?>

        <button class="btn-primary" type="submit">Procéder au paiement</button>
    </form>

    <p style="margin-top: 1em;"><a href="trips_details.php?trip_id=<?= urlencode($tripId) ?>">← Modifier mes choix</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
