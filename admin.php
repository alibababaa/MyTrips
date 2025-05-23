<?php
session_start();

// Vérifie que l'utilisateur est connecté et est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$usersFile = "users.json";
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

//  TRAITEMENT AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'], $_POST['action'], $_POST['login'])) {
    sleep(3); // Simuler une latence serveur

    $action = $_POST['action'];
    $login = $_POST['login'];
    $newRole = null;

    foreach ($users as &$user) {
        if ($user['login'] === $login && $login !== $_SESSION['user']['login']) {
            if ($action === 'vip') {
                $user['role'] = 'vip';
            } elseif ($action === 'removevip' || $action === 'user') {
                $user['role'] = 'user';
            } elseif ($action === 'banni') {
                $user['role'] = 'banni';
            }

            $newRole = $user['role'];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

            echo json_encode(['success' => true, 'newRole' => $newRole]);
            exit();
        }
    }

    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable ou action interdite.']);
    exit();
}

// TRAITEMENT CLASSIQUE GET (fallback ou compatibilité)
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
                            <button class="simulate-update" data-role="vip" data-login="<?= $user['login'] ?>">VIP</button>
                            <button class="simulate-update" data-role="banni" data-login="<?= $user['login'] ?>">Bannir</button>
                        <?php elseif ($user['role'] === 'vip'): ?>
                            <button class="simulate-update" data-role="user" data-login="<?= $user['login'] ?>">Enlever VIP</button>
                            <button class="simulate-update" data-role="banni" data-login="<?= $user['login'] ?>">Bannir</button>
                        <?php elseif ($user['role'] !== 'banni'): ?>
                            <button class="simulate-update" data-role="banni" data-login="<?= $user['login'] ?>">Bannir</button>
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
<script src="simulateUpdate.js"></script>
</body>
</html>
