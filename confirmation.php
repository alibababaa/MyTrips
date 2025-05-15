<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$userLogin = $_SESSION['user']['login'] ?? '';
$transactionsFile = __DIR__ . '/transactions.json';
$tripsFile = __DIR__ . '/trips.json';

$transactions = file_exists($transactionsFile) ? json_decode(file_get_contents($transactionsFile), true) : [];
$trips = file_exists($tripsFile) ? json_decode(file_get_contents($tripsFile), true) : [];

// Vérifie si un batch_id est passé dans l’URL
$batchId = $_GET['batch'] ?? null;
$userRecentTrips = [];

if ($batchId) {
    foreach ($transactions as $t) {
        if ($t['user_id'] === $userLogin && isset($t['batch_id']) && $t['batch_id'] === $batchId) {
            $userRecentTrips[] = $t;
        }
    }
} else {
    // Fallback : dernière date de paiement
    $userTransactions = array_filter($transactions, function ($t) use ($userLogin) {
        return $t['user_id'] === $userLogin;
    });

    usort($userTransactions, function ($a, $b) {
        return strtotime($b['payment_date']) <=> strtotime($a['payment_date']);
    });

    if (!empty($userTransactions)) {
        $latestDate = $userTransactions[0]['payment_date'];
        foreach ($userTransactions as $t) {
            if ($t['payment_date'] === $latestDate) {
                $userRecentTrips[] = $t;
            }
        }
    }
}

function findTripTitle($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip['titre'] ?? "Inconnu";
    }
    return "Inconnu";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-confirmation">

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
        <h1>Merci pour votre réservation !</h1>
        <p>Votre paiement a bien été pris en compte.</p>
    </div>
</header>

<section style="text-align: center; padding: 2em;">
    <h2>🎉 Réservation Confirmée</h2>
    <?php if (!empty($userRecentTrips)): ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($userRecentTrips as $t): ?>
                <li style="margin: 1em 0; padding: 1em; border: 1px solid #ccc; border-radius: 10px;">
                    ✅ <?= htmlspecialchars(findTripTitle($trips, $t['trip_id'])) ?> – <?= number_format($t['montant'], 2, ',', ' ') ?> €<br>
                    <small>Réservé le <?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($t['payment_date']))) ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune nouvelle réservation trouvée.</p>
    <?php endif; ?>
    <a href="mon_profil.php" class="btn-primary" style="margin-top: 1.5em; display: inline-block;">Voir mes réservations</a>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
