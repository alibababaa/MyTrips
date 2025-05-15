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

// Vérification des données nécessaires
if (!isset($_POST['trip_id'], $_POST['nb_personnes'])) {
    die("Données incomplètes.");
}

$tripId = $_POST['trip_id'];
$nbPersonnes = max(1, (int)$_POST['nb_personnes']);
$etapes = isset($_POST['etapes']) && is_array($_POST['etapes']) ? $_POST['etapes'] : [];
$options = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : [];
$hebergement = $_POST['hebergement'] ?? '';
$repas = $_POST['repas'] ?? '';
$activites = $_POST['activites'] ?? '';
$prixEstime = isset($_POST['prix_estime']) ? (float)$_POST['prix_estime'] : null;

// Recherche du voyage
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

// Calcul du prix si non fourni
if (is_null($prixEstime)) {
    $prixBase = (int)($tripDetails['prix'] ?? 0);
    $prixParEtape = 10;
    $prixEstime = $prixBase * $nbPersonnes + count($etapes) * $prixParEtape;

    foreach ($options as $option) {
        switch ($option) {
            case 'assurance': $prixEstime += 20 * $nbPersonnes; break;
            case 'bagage': $prixEstime += 30 * $nbPersonnes; break;
            case 'guide': $prixEstime += 50; break;
            case 'transport': $prixEstime += 100; break;
            case 'premium': $prixEstime += 40 * $nbPersonnes; break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Récapitulatif de la réservation - My Trips</title>
    <link rel="stylesheet" href="my_trips.css">
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
        <h1>Récapitulatif de la réservation</h1>
        <p>Vérifiez les détails avant de confirmer votre réservation</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: 2em auto; text-align: center;">
    <h2><?= htmlspecialchars($tripDetails['titre']) ?></h2>
    <?php if (!empty($tripDetails['image'])): ?>
        <img src="<?= htmlspecialchars($tripDetails['image']) ?>" alt="Image du voyage"
             style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 1em;">
    <?php endif; ?>

    <p><strong>Nombre de personnes :</strong> <?= $nbPersonnes ?></p>
    <p><strong>Hébergement choisi :</strong> <?= htmlspecialchars($hebergement ?: 'Non spécifié') ?></p>
    <p><strong>Repas choisi :</strong> <?= htmlspecialchars($repas ?: 'Non spécifié') ?></p>
    <p><strong>Activités choisies :</strong> <?= htmlspecialchars($activites ?: 'Non spécifié') ?></p>

    <?php if (!empty($etapes)): ?>
        <p><strong>Étapes sélectionnées :</strong> <?= implode(', ', array_map('htmlspecialchars', $etapes)) ?></p>
    <?php endif; ?>

    <?php if (!empty($options)): ?>
        <p><strong>Options supplémentaires :</strong> <?= implode(', ', array_map('htmlspecialchars', $options)) ?></p>
    <?php endif; ?>

    <p style="font-size: 1.5em; font-weight: bold;">Prix total estimé : <?= number_format($prixEstime, 2, ',', ' ') ?> €</p>

    <form action="paiement.php" method="POST">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
        <input type="hidden" name="nb_personnes" value="<?= $nbPersonnes ?>">
        <input type="hidden" name="prix_total" value="<?= htmlspecialchars($prixEstime) ?>">
        <input type="hidden" name="hebergement" value="<?= htmlspecialchars($hebergement) ?>">
        <input type="hidden" name="repas" value="<?= htmlspecialchars($repas) ?>">
        <input type="hidden" name="activites" value="<?= htmlspecialchars($activites) ?>">
        <?php foreach ($etapes as $etape): ?>
            <input type="hidden" name="etapes[]" value="<?= htmlspecialchars($etape) ?>">
        <?php endforeach; ?>
        <?php foreach ($options as $option): ?>
            <input type="hidden" name="options[]" value="<?= htmlspecialchars($option) ?>">
        <?php endforeach; ?>

        <button class="btn-primary" type="submit">Procéder au paiement</button>
    </form>

    <p style="margin-top: 1.5em;"><a href="reserver.php">← Retour aux voyages</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>

