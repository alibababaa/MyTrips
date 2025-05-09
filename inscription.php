<?php
session_start();

$usersFile = 'users.json';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Tous les champs doivent être remplis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Adresse email invalide.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        // Vérifier que l'email n'existe pas déjà
        foreach ($users as $user) {
            if ($user['login'] === $email) {
                $error_message = "Cet email est déjà utilisé.";
                break;
            }
        }

        if (empty($error_message)) {
            $newUser = [
                'login'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'name'     => $name,
                'role'     => 'user'
            ];

            $users[] = $newUser;
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

            $_SESSION['user'] = $newUser;
            header('Location: accueil.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription - My Trips</title>
  <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
  <script src="theme.js" defer></script>
  <script src="password-toggle.js" defer></script>
</head>
<body class="page-inscription">

<nav>
  <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"/></div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="présentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>
    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else: ?>
      <li><a class="active" href="inscription.php">S'inscrire</a></li>
      <li><a href="connexion.php">Se connecter</a></li>
    <?php endif; ?>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    <li>
      <button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
    </li>
  </ul>
</nav>

<header class="banner">
  <div class="banner-content">
    <h1>Rejoignez-nous dès aujourd'hui</h1>
    <p>Créez un compte pour accéder à nos offres exclusives.</p>
  </div>
</header>

<section class="signup-section">
  <h2>Inscription</h2>

  <?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
  <?php endif; ?>

  <form method="POST" action="inscription.php">
    <label for="name">Nom :</label>
    <input id="name" name="name" placeholder="Votre nom complet" required type="text"/>

    <label for="email">Email :</label>
    <input id="email" name="email" placeholder="Votre email" required type="email"/>

   <label for="password">Mot de passe :</label>
<div style="position: relative;">
  <input id="password" name="password" placeholder="Créer un mot de passe" required type="password" style="padding-right: 40px;" />
  <span onclick="togglePassword('password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
</div>

<label for="confirm-password">Confirmer le mot de passe :</label>
<div style="position: relative;">
  <input id="confirm-password" name="confirm-password" placeholder="Confirmez votre mot de passe" required type="password" style="padding-right: 40px;" />
  <span onclick="togglePassword('confirm-password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
</div>


    <button class="btn-primary" type="submit">S'inscrire</button>
  </form>

  <p class="login-link">
    Déjà un compte ? <a href="connexion.php">Connectez-vous ici</a>
  </p>
</section>

<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
