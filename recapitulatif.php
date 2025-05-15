<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_POST['trip_id'], $_POST['nb_personnes'], $_POST['prix_estime'])) {
    die("Données manquantes.");
}

$tripId = $_POST['trip_id'];
$nbPersonnes = (int)$_POST['nb_personnes'];
$prixEstime = (float)$_POST['prix_estime'];
$options = $_POST['options'] ?? [];
$etapes = $_POST['etapes'] ?? [];
$hebergement = $_POST['hebergement'] ?? '';
$repas = $_POST['repas'] ?? '';
$activites = $_POST['activites'] ?? '';

// Optionnel : tu peux recharger ici les infos du voyage si besoin pour afficher le titre ou autre
function loadTrips() {
    $file = __DIR__ . '/trips.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

$trips = loadTrips();
$tripDetails = null;
foreach ($trips as $trip) {
    if (isset($trip['id']) && $trip['id'] == $tripId) {
        $tripDetails = $trip;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Récapitulatif de la réservation - My Trips</title>
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
        <h1>Récapitulatif de la réservation</h1>
        <p>Vérifiez les détails avant de confirmer votre réservation</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: 2em auto; text-align: center;">
    <?php if ($tripDetails): ?>
        <h2><?= htmlspecialchars($tripDetails['titre']) ?></h2>
        <?php if (!empty($tripDetails['image'])): ?>
            <img src="<?= htmlspecialchars($tripDetails['image']) ?>" alt="Image du voyage" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 1em;">
        <?php endif; ?>
    <?php else: ?>
        <h2>Voyage sélectionné</h2>
    <?php endif; ?>

    <p><strong>Nombre de personnes :</strong> <?= $nbPersonnes ?></p>
    <p><strong>Hébergement choisi :</strong> <?= htmlspecialchars($hebergement) ?></p>
    <p><strong>Repas choisi :</strong> <?= htmlspecialchars($repas) ?></p>
    <p><strong>Activités choisies :</strong> <?= htmlspecialchars($activites) ?></p>

    <?php if (!empty($etapes)): ?>
        <p><strong>Étapes sélectionnées :</strong> <?= htmlspecialchars(implode(', ', $etapes)) ?></p>
    <?php endif; ?>

    <?php if (!empty($options)): ?>
        <p><strong>Options supplémentaires :</strong> <?= htmlspecialchars(implode(', ', $options)) ?></p>
    <?php endif; ?>

    <p style="font-size: 1.5em; font-weight: bold;">Prix total estimé : <?= number_format($prixEstime, 2, ',', ' ') ?> €</p>

    <form action="paiement.php" method="POST">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripId) ?>">
        <input type="hidden" name="nb_personnes" value="<?= $nbPersonnes ?>">
        <input type="hidden" name="prix_total" value="<?= htmlspecialchars($prixEstime) ?>">
        <input type="hidden" name="hebergement" value="<?= htmlspecialchars($hebergement) ?>">
        <input type="hidden" name="repas" value="<?= htmlspecialchars($repas) ?>">
        <input type="hidden" name="activites" value="<?= htmlspecialchars($activites) ?>">
        <?php
        if (!empty($etapes)) {
            foreach ($etapes as $etape) {
                echo '<input type="hidden" name="etapes[]" value="'.htmlspecialchars($etape).'">';
            }
        }
        if (!empty($options)) {
            foreach ($options as $option) {
                echo '<input type="hidden" name="options[]" value="'.htmlspecialchars($option).'">';
            }
        }
        ?>
        <button class="btn-primary" type="submit">Procéder au paiement</button>
    </form>

    <p style="margin-top: 1.5em;"><a href="reserver.php">← Retour aux voyages</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
