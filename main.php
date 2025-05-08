<?php
session_start();

// Fonctions
function loadUsers() {
    return file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];
}

function saveUser($user) {
    $users = loadUsers();
    $users[] = $user;
    file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
}

function loadTrips() {
    return file_exists("trips.json") ? json_decode(file_get_contents("trips.json"), true) : [];
}

// Gestion inscription
if (isset($_POST['register'])) {
    $name     = $_POST['name'];
    $login    = $_POST['login'];
    $password = $_POST['password'];

    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['login'] === $login) {
            $register_error = "Ce login est déjà pris.";
            break;
        }
    }

    if (!isset($register_error)) {
        $newUser = [
            "login"    => $login,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role"     => "user",
            "name"     => $name
        ];
        saveUser($newUser);
        $_SESSION['user'] = $newUser;
        $register_success = "Inscription réussie. Bienvenue, $name!";
    }
}

// Gestion connexion
if (isset($_POST['login_user'])) {
    $login    = $_POST['login'];
    $password = $_POST['password'];
    $users    = loadUsers();

    foreach ($users as $user) {
        if ($user['login'] === $login && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $login_success = "Bienvenue, {$user['name']} !";
            break;
        }
    }

    if (!isset($_SESSION['user'])) {
        $login_error = "Login ou mot de passe incorrect.";
    }
}

// Gestion déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: main.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body>
<nav>
    <ul>
        <li><a class="active" href="main.php">Accueil</a></li>
        <li><a href="#trips">Voyages</a></li>
        <li><a href="#signup">Inscription</a></li>
        <li><a href="#login">Connexion</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="?logout=true">Déconnexion</a></li>
        <?php endif; ?>
        <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
    </ul>
</nav>

<!-- Section d'inscription -->
<section id="signup">
    <h2>Inscription</h2>
    <?php if (!empty($register_error)) echo "<p style='color:red;'>$register_error</p>"; ?>
    <?php if (!empty($register_success)) echo "<p style='color:green;'>$register_success</p>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Nom" required><br>
        <input type="text" name="login" placeholder="Email / Identifiant" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit" name="register">S'inscrire</button>
    </form>
</section>

<!-- Section de connexion -->
<section id="login">
    <h2>Connexion</h2>
    <?php if (!empty($login_error)) echo "<p style='color:red;'>$login_error</p>"; ?>
    <?php if (!empty($login_success)) echo "<p style='color:green;'>$login_success</p>"; ?>
    <form method="POST">
        <input type="text" name="login" placeholder="Login" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit" name="login_user">Se connecter</button>
    </form>
</section>

<!-- Liste des voyages -->
<section id="trips">
    <h2>Voyages disponibles</h2>
    <?php
    $trips = loadTrips();
    if (!empty($trips)) {
        foreach ($trips as $trip) {
            echo "<div style='margin-bottom: 1.5em;'>";
            echo "<h3>" . htmlspecialchars($trip['titre']) . "</h3>";
            echo "<p>Prix : " . htmlspecialchars($trip['prix']) . "€</p>";
            echo "<p>Durée : " . htmlspecialchars($trip['duree']) . " jours</p>";
            echo "<a class='btn-primary' href='trips_details.php?trip_id=" . urlencode($trip['id']) . "'>Voir plus</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucun voyage disponible actuellement.</p>";
    }
    ?>
</section>

<!-- Bienvenue utilisateur -->
<?php if (isset($_SESSION['user'])): ?>
    <section>
        <h2>Bonjour, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
        <p><a href="?logout=true" class="btn-primary">Se déconnecter</a></p>
    </section>
<?php endif; ?>

<footer>
    <p>&copy; 2025 My Trips</p>
</footer>
</body>
</html>
