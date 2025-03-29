<?php
session_start();

// Vérifie que l'utilisateur est connecté et est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

$users = json_decode(file_get_contents("users.json"), true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Admin - Gestion des Utilisateurs</title>
    <link href="my_trips.css" rel="stylesheet"/>
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
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <button class="btn-vip">VIP</button>
                    <button class="btn-ban">Bannir</button>
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
