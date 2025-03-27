<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

/**
 * main.php
 * Contient des fonctions globales pour les utilisateurs et les voyages.
 */

// Fonction pour charger les utilisateurs depuis le fichier JSON
function loadUsers() {
    $file = __DIR__ . '/../data/users.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

// Fonction pour enregistrer un nouvel utilisateur
function saveUser($user) {
    $users = loadUsers();
    $users[] = $user;
    file_put_contents(__DIR__ . '/../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
}

// Fonction pour charger les voyages depuis le fichier JSON
function loadTrips() {
    $file = __DIR__ . '/../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

// Fonction pour enregistrer un nouveau voyage
function saveTrip($trip) {
    $trips = loadTrips();
    $trips[] = $trip;
    file_put_contents(__DIR__ . '/../data/trips.json', json_encode($trips, JSON_PRETTY_PRINT));
}

// -- Formulaires d'inscription / connexion ci-dessous (version simplifiée) --

// Inscription
if (isset($_POST['register'])) {
    $login    = $_POST['login']    ?? '';
    $password = $_POST['password'] ?? '';
    $role     = 'user';
    $name     = $_POST['name']     ?? '';

    $users = loadUsers();
    foreach ($users as $u) {
        if ($u['login'] === $login) {
            die('Ce login est déjà pris.');
        }
    }

    $newUser = [
        "login"    => $login,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "role"     => $role,
        "name"     => $name
    ];
    saveUser($newUser);
    echo 'Inscription réussie, vous pouvez vous connecter.';
}

// Connexion
if (isset($_POST['login_user'])) {
    $login    = $_POST['login']    ?? '';
    $password = $_POST['password'] ?? '';
    $users    = loadUsers();
    
    foreach ($users as $u) {
        if ($u['login'] === $login && password_verify($password, $u['password'])) {
            $_SESSION['user'] = $u; // Stocker l'user complet
            header('Location: dashboard.php');
            exit;
        }
    }
    echo 'Login ou mot de passe incorrect';
}

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!--
Page d'inscription (exemple) :
Vous pouvez découper ce HTML dans un autre fichier si vous voulez séparer logiquement.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="name" required><br>
        <label>Login :</label>
        <input type="text" name="login" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="register">S'inscrire</button>
    </form>

    <h2>Connexion</h2>
    <form method="POST">
        <label>Login :</label>
        <input type="text" name="login" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="login_user">Se connecter</button>
    </form>
</body>
</html>
