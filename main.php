<?php
session_start();

// Fonction pour charger les utilisateurs depuis le fichier JSON
function loadUsers() {
    $file = '../data/users.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

// Fonction pour enregistrer un nouvel utilisateur
function saveUser($user) {
    $users = loadUsers();
    $users[] = $user;
    file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
}

// Fonction pour charger les voyages depuis le fichier JSON
function loadTrips() {
    $file = '../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

// Fonction pour enregistrer un nouveau voyage
function saveTrip($trip) {
    $trips = loadTrips();
    $trips[] = $trip;
    file_put_contents('../data/trips.json', json_encode($trips, JSON_PRETTY_PRINT));
}

// Inscription
if (isset($_POST['register'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $role = 'user';
    
    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['login'] === $login) {
            die('Ce login est déjà pris.');
        }
    }
    
    $newUser = [
        "login" => $login,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "role" => $role,
        "name" => $_POST['name']
    ];
    
    saveUser($newUser);
    echo 'Inscription réussie, vous pouvez vous connecter.';
}

// Connexion
if (isset($_POST['login_user'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['login'] === $login && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
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

<!-- Page d'inscription -->
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
</body>
</html>

<!-- Page de connexion -->
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
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

<!-- Page des voyages -->
<!DOCTYPE html>
<html>
<head>
    <title>Liste des voyages</title>
</head>
<body>
    <h2>Voyages disponibles</h2>
    <?php
    $trips = loadTrips();
    foreach ($trips as $trip) {
        echo '<div>';
        echo '<h3>' . $trip['titre'] . '</h3>';
        echo '<p>Prix: ' . $trip['prix'] . '€</p>';
        echo '<p>Durée: ' . $trip['duree'] . ' jours</p>';
        echo '<a href="trip_details.php?id=' . $trip['id'] . '">Voir plus</a>';
        echo '</div>';
    }
    ?>
</body>
</html>

<!-- Page tableau de bord -->
<?php
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord</title>
</head>
<body>
    <h2>Bienvenue, <?php echo $_SESSION['user']['name']; ?>!</h2>
    <a href="?logout=true">Se déconnecter</a>
</body>
</html>

<!-- Page détails voyage -->
<?php
$trips = loadTrips();
$tripId = $_GET['id'] ?? null;
$tripDetails = null;
foreach ($trips as $trip) {
    if ($trip['id'] == $tripId) {
        $tripDetails = $trip;
        break;
    }
}
if (!$tripDetails) {
    die('Voyage non trouvé.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Détails du voyage</title>
</head>
<body>
    <h2><?php echo $tripDetails['titre']; ?></h2>
    <p>Prix: <?php echo $tripDetails['prix']; ?>€</p>
    <p>Durée: <?php echo $tripDetails['duree']; ?> jours</p>
    <p>Liste des étapes: <?php echo implode(", ", $tripDetails['etapes']); ?></p>
    <a href="trips.php">Retour à la liste des voyages</a>
</body>
</html>
