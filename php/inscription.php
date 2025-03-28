<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = $_POST['name']             ?? '';
    $email            = $_POST['email']            ?? '';
    $password         = $_POST['password']         ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Tous les champs doivent être remplis.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        // Simuler l'ajout en session
        $_SESSION['user'] = [
            'login'  => $email,
            'prenom' => $name,
            'role'   => 'user'
        ];

        header('Location: accueil.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet"/>
</head>
<body>
  
<!-- Navigation -->
<nav>
  <div class="logo">
    <img alt="My Trips Logo" src="logo_my_trips.png"/>
  </div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="presentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>

    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else: ?>
      <li><a class="active" href="inscription.php">S'inscrire</a></li>
      <li><a href="connexion.php">Se connecter</a></li>
    <?php endif; ?>

    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
  </ul>
</nav>
  
<!-- Banner -->
<header class="banner">
  <div class="banner-content">
    <h1>Rejoignez-nous dès aujourd'hui</h1>
    <p>Créez un compte pour accéder à nos offres exclusives.</p>
  </div>
</header>

<!-- Formulaire d'inscription -->
<section class="signup-section">
  <h2>Inscription</h2>
  
  <?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php endif; ?>
  
  <form method="POST" action="inscription.php">
    <label for="name">Nom :</label>
    <input id="name" name="name" placeholder="Votre nom complet" required type="text"/>
    
    <label for="email">Email :</label>
    <input id="email" name="email" placeholder="Votre email" required type="email"/>
    
    <label for="password">Mot de passe :</label>
    <input id="password" name="password" placeholder="Créer un mot de passe" required type="password"/>
    
    <label for="confirm-password">Confirmer le mot de passe :</label>
    <input id="confirm-password" name="confirm-password" placeholder="Confirmez votre mot de passe" required type="password"/>
    
    <button class="btn-primary" type="submit">S'inscrire</button>
  </form>
  
  <p class="login-link">
    Déjà un compte ? <a href="connexion.php">Connectez-vous ici</a>
  </p>
</section>
  
<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
