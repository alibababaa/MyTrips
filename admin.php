<?php
session_start();

// Vérifie que l'utilisateur est connecté et est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$usersFile = "users.json";
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Traitement des actions
if (isset($_GET['action'], $_GET['login'])) {
    $action = $_GET['action'];
    $login = $_GET['login'];

    foreach ($users as &$user) {
        if ($user['login'] === $login && $login !== $_SESSION['user']['login']) {
            if ($action === 'vip') {
                $user['role'] = 'vip';
            } elseif ($action === 'removevip') {
                $user['role'] = 'user';
            } elseif ($action === 'ban') {
                $user['role'] = 'banni';
            }
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            break;
        }
    }

    // Redirection pour éviter la répétition d'action au refresh
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Admin - Gestion des Utilisateurs</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-admin">

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li>
            <button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">
                🌓
            </button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Panneau d'administration</h1>
        <p>Gérez les utilisateurs et leurs privilèges.</p>
    </div>
</header>

<section class="admin-section">
    <h2>Liste des utilisateurs</h2>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Login</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <?php if ($user['login'] !== $_SESSION['user']['login']): ?>
                        <?php if ($user['role'] === 'user'): ?>
                            <a href="?action=vip&login=<?= urlencode($user['login']) ?>" class="btn-vip">VIP</a>
                        <?php elseif ($user['role'] === 'vip'): ?>
                            <a href="?action=removevip&login=<?= urlencode($user['login']) ?>" class="btn-remove-vip">Enlever VIP</a>
                        <?php endif; ?>
                        <?php if ($user['role'] !== 'banni'): ?>
                            <a href="?action=ban&login=<?= urlencode($user['login']) ?>" class="btn-ban">Bannir</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <em>(vous)</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
