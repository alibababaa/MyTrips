<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$file_path = __DIR__ . '/trips.json';
$trips = json_decode(file_get_contents($file_path), true);
if (!is_array($trips)) $trips = [];

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}

$selectedTrips = [];
$tripIdsFromForm = $_POST['trip_ids'] ?? ($_POST['trip_id'] ?? null);

$nbPersonnes = isset($_POST['nb_personnes']) ? max(1, (int) $_POST['nb_personnes']) : 1;
$optionsChoisies = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : [];

if (!empty($tripIdsFromForm)) {
    if (!is_array($tripIdsFromForm)) {
        $tripIdsFromForm = [$tripIdsFromForm];
    }

    foreach ($tripIdsFromForm as $tripId) {
        $trip = findTripById($trips, $tripId);
        if ($trip) {
            $selectedTrips[] = $trip;
        }
    }

    if (empty($selectedTrips)) {
        exit("Erreur : Aucun voyage valide sélectionné.");
    }
} else {
    exit("Erreur : Aucun voyage sélectionné.");
}

// Calcul du prix total
$prixTotal = 0;

foreach ($selectedTrips as $trip) {
    $prixBase = (int) $trip['prix'];
    $prixVoyage = $prixBase * $nbPersonnes;

    // Prix des options générales
    foreach ($optionsChoisies as $option) {
        switch ($option) {
            case 'assurance':
                $prixVoyage += 20 * $nbPersonnes;
                break;
            case 'bagage':
                $prixVoyage += 30 * $nbPersonnes;
                break;
            case 'guide':
                $prixVoyage += 50;
                break;
            case 'transport':
                $prixVoyage += 100;
                break;
            case 'premium':
                $prixVoyage += 40 * $nbPersonnes;
                break;
        }
    }

    $prixTotal += $prixVoyage;
}

// Traitement du paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_number'], $_POST['card_owner'], $_POST['expiry_date'], $_POST['cvv'])) {
    // Vérifie que le prix total est transmis
    if ($prixTotal === null) {
        exit("Erreur : prix total non fourni.");
    }

    $paymentSuccessful = true; // Simulation du paiement

    if ($paymentSuccessful) {
        $transactions_path = __DIR__ . '/transactions.json';
        $transactions = file_exists($transactions_path) ? json_decode(file_get_contents($transactions_path), true) : [];

        foreach ($selectedTrips as $trip) {
            $transactions[] = [
                'user_id' => $_SESSION['user']['login'],
                'trip_id' => $trip['id'],
                'payment_date' => date('Y-m-d H:i:s'),
                'montant' => $prixTotal
            ];
        }

        file_put_contents($transactions_path, json_encode($transactions, JSON_PRETTY_PRINT));

        if (isset($_POST['trip_ids']) && isset($_SESSION['panier'])) {
            unset($_SESSION['panier']);
        }

        header('Location: confirmation.php');
        exit;
    } else {
        header('Location: erreurpaiement.php');
        exit;
    }
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
        <h1>Récapitulatif</h1>
        <p>Voyages sélectionnés pour le paiement</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: auto;">
    <?php 
    foreach ($selectedTrips as $trip): 
        $prixBase = (int) $trip['prix'];
        $prixVoyage = $prixBase * $nbPersonnes;

        // Calcul du prix total pour le voyage incluant les options
        $prixOptions = 0;
        if (!empty($optionsChoisies)) {
            foreach ($optionsChoisies as $option) {
                // On suppose que chaque option a un prix spécifique dans les données du voyage
                if (isset($trip['options'][$option])) {
                    $prixOptions += $trip['options'][$option]; // Ajout du prix de l'option choisie
                }
            }
        }
        $prixVoyage += $prixOptions;
    ?>
        <div style="border: 1px solid #ddd; padding: 1em; margin: 1em 0; border-radius: 10px;">
            <h3><?= htmlspecialchars($trip['titre']) ?></h3>
            <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
            <p><strong>Prix par personne :</strong> <?= htmlspecialchars($prixBase) ?> €</p>
            <p><strong>Nombre de personnes :</strong> <?= htmlspecialchars($nbPersonnes) ?></p>
            
<p><strong>Options choisies :</strong>
<?php
if (empty($optionsChoisies)) {
    echo "Aucune";
} else {
    $descriptions = [];
    foreach ($optionsChoisies as $opt) {
        switch ($opt) {
            case 'assurance': $prix = 20 * $nbPersonnes; break;
            case 'bagage': $prix = 30 * $nbPersonnes; break;
            case 'guide': $prix = 50; break;
            case 'transport': $prix = 100; break;
            case 'premium': $prix = 40 * $nbPersonnes; break;
            default: $prix = 0;
        }
        $descriptions[] = $opt . " (" . $prix . "€)";
    }
    echo implode(', ', $descriptions);
}
?>
</p>

            <p><strong>Prix total pour ce voyage :</strong> <?= number_format($prixVoyage, 2) ?> €</p>
        </div>
    <?php endforeach; ?>
    
    <h3>Prix Total à Payer : <?= number_format($prixTotal, 2) ?> €</h3>
</section>

<h2 style="text-align: center;">Formulaire de Paiement</h2>
<form action="paiement.php" method="POST" style="max-width: 600px; margin: 2em auto;">
    <h3>Coordonnées Bancaires</h3>

    <label for="card_number">Numéro de carte (16 chiffres)</label>
    <input type="text" name="card_number" id="card_number" pattern="\d{16}" required placeholder="1234567812345678">

    <label for="card_owner">Nom et prénom du propriétaire de la carte</label>
    <input type="text" name="card_owner" id="card_owner" required placeholder="Jean Dupont">

    <label for="expiry_date">Date d'expiration (MM/AA)</label>
    <input type="text" name="expiry_date" id="expiry_date" pattern="\d{2}/\d{2}" required placeholder="MM/AA">

    <label for="cvv">Valeur de contrôle (CVV - 3 chiffres)</label>
    <input type="text" name="cvv" id="cvv" pattern="\d{3}" required placeholder="123">

    <?php foreach ($selectedTrips as $trip): ?>
        <input type="hidden" name="trip_ids[]" value="<?= htmlspecialchars($trip['id']) ?>">
    <?php endforeach; ?>

    <input type="hidden" name="prix_total" value="<?= htmlspecialchars($prixTotal) ?>">

    <button type="submit" class="btn-primary">Payer</button>
</form>

<footer>
    <p>&copy; 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>

