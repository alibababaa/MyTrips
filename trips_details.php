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

if (!isset($_GET['trip_id'])) {
    die("Aucun voyage sélectionné.");
}

$tripId = $_GET['trip_id'];
$mode = $_GET['mode'] ?? 'reserver';

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
  <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"/></div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="présentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>
    <li><a href="mon_profil.php">Mon Profil</a></li>
    <li><a href="mon_panier.php">Mon Panier</a></li>
    <li><a href="deconnexion.php">Se déconnecter</a></li>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
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

    <p><strong>Prix de base :</strong> <?= htmlspecialchars($tripDetails['prix']) ?> €</p>
    <p><strong>Durée :</strong> <?= htmlspecialchars($tripDetails['duree']) ?> jours</p>

    <?php if (!empty($tripDetails['etapes']) && is_array($tripDetails['etapes'])): ?>
        <p><strong>Étapes :</strong> <?= htmlspecialchars(implode(', ', $tripDetails['etapes'])) ?></p>
    <?php endif; ?>

    <form action="<?= $mode === 'panier' ? 'ajouter_panier.php' : 'recapitulatif.php' ?>" method="POST">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripDetails['id']) ?>">
        <input type="hidden" name="prix_estime" id="prix_estime_input">
        <input type="hidden" name="from_details" value="1">

        <label for="nb_personnes"><strong>Nombre de personnes :</strong></label>
        <input type="number" id="nb_personnes" name="nb_personnes" value="1" min="1" required><br><br>

        <?php if (!empty($tripDetails['etapes']) && is_array($tripDetails['etapes'])): ?>
            <label><strong>Choisissez vos étapes (chacune +10 €) :</strong></label><br>
            <?php foreach ($tripDetails['etapes'] as $etape): ?>
                <input type="checkbox" name="etapes[]" value="<?= htmlspecialchars($etape) ?>" checked>
                <?= htmlspecialchars($etape) ?> <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <label><strong>Options supplémentaires :</strong></label><br>
        <input type="checkbox" name="options[]" value="assurance"> Assurance voyage (+20 €/pers)<br>
        <input type="checkbox" name="options[]" value="bagage"> Bagage en soute (+30 €/pers)<br>
        <input type="checkbox" name="options[]" value="guide"> Guide local (+50 €)<br>
        <input type="checkbox" name="options[]" value="transport"> Transport privé (+100 €)<br>
        <input type="checkbox" name="options[]" value="premium"> Hébergement premium (+40 €/pers)<br>

        <br>
        <p><strong>Prix estimé :</strong> <span id="prix-estime">0 €</span></p>

        <button class="btn-primary" type="submit">
            <?= $mode === 'panier' ? 'Ajouter au panier 🛒' : 'Voir le récapitulatif' ?>
        </button>
    </form>

    <p style="margin-top: 1.5em;"><a href="reserver.php">← Retour aux voyages</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nbPersonnesInput = document.getElementById('nb_personnes');
    const checkboxesEtapes = document.querySelectorAll('input[name="etapes[]"]');
    const checkboxesOptions = document.querySelectorAll('input[name="options[]"]');
    const prixEstime = document.getElementById('prix-estime');
    const prixBase = <?= json_encode((int)$tripDetails['prix']) ?>;
    const prixParEtape = 10;

    function calculerPrix() {
        const nb = parseInt(nbPersonnesInput.value) || 1;
        let total = prixBase * nb;

        checkboxesEtapes.forEach(cb => {
            if (cb.checked) total += prixParEtape;
        });

        checkboxesOptions.forEach(cb => {
            if (cb.checked) {
                if (cb.value === 'assurance') total += 20 * nb;
                if (cb.value === 'bagage') total += 30 * nb;
                if (cb.value === 'premium') total += 40 * nb;
                if (cb.value === 'guide') total += 50;
                if (cb.value === 'transport') total += 100;
            }
        });

        prixEstime.textContent = total + " €";
        document.getElementById('prix_estime_input').value = total;
    }

    nbPersonnesInput.addEventListener('input', calculerPrix);
    checkboxesEtapes.forEach(cb => cb.addEventListener('change', calculerPrix));
    checkboxesOptions.forEach(cb => cb.addEventListener('change', calculerPrix));

    calculerPrix();
});
</script>

</body>
</html>
