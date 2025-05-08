<?php session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$success = false;

if (isset($_GET['trip_id'])) {
    $tripId = $_GET['trip_id'];
    $userId = $_SESSION['user']['login'];
    $paymentDetails = [
        'user_id' => $userId,
        'trip_id' => $tripId,
        'payment_date' => date('Y-m-d H:i:s'),
    ];

    $path = __DIR__ . '/transactions.json';
    $transactions = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    $transactions[] = $paymentDetails;
    file_put_contents($path, json_encode($transactions, JSON_PRETTY_PRINT));

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation Paiement - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="">
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
        <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Confirmation de Réservation</h1>
        <p>Merci pour votre confiance !</p>
    </div>
</header>

<section style="text-align: center; padding: 2em;">
    <?php if ($success): ?>
        <h2>Paiement enregistré ✅</h2>
        <p>Votre réservation a bien été confirmée.</p>
        <a class="btn-primary" href="mon_profil.php">Voir mes réservations</a>
    <?php else: ?>
        <h2>Erreur ❌</h2>
        <p>Impossible d'enregistrer cette transaction.</p>
        <a class="btn-primary" href="reserver.php">Retour à la réservation</a>
    <?php endif; ?>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
