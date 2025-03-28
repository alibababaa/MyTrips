<?php
session_start();

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Exemple de récupération des données du formulaire
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    // Exemple de validation (à remplacer par une vraie vérification)
    if ($email == 'utilisateur@example.com' && $password == 'motdepasse') {
        // Connexion réussie, stocker l'utilisateur en session SOUS FORME DE TABLEAU
        $_SESSION['user'] = [
            'login'  => $email,
            'prenom' => 'Jean',   // ex. on simule un "prenom"
            'role'   => 'user'
        ];
        header('Location: accueil.php'); // Redirige vers la page d'accueil
        exit;
    } else {
        $error_message = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Connexion - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&amp;display=swap" rel="stylesheet"/>
</head>
<body>
  
<!-- Navigation -->
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
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a class="active" href="connexion.php">Se connecter</a></li>
    <?php endif; ?>

    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
  </ul>
</nav>
  
<!-- Banner -->
<header class="banner">
  <div class="banner-content">
    <h1>Connectez-vous à votre compte</h1>
    <p>Accédez à vos réservations et à vos offres exclusives.</p>
  </div>
</header>
  
<!-- Formulaire de connexion -->
<section class="login-section">
  <h2>Connexion</h2>
  
  <?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
  <?php endif; ?>

  <form method="POST" action="connexion.php">
    <label for="email">Email :</label>
    <input id="email" name="email" placeholder="Votre email" required="" type="email"/>
    
    <label for="password">Mot de passe :</label>
    <input id="password" name="password" placeholder="Votre mot de passe" required="" type="password"/>
    
    <p class="forgot-password"><a href="reset-password.php">Mot de passe oublié ?</a></p>
    <button class="btn-primary" type="submit">Se connecter</button>
  </form>
  
  <p class="register-link">
    Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a>
  </p>
</section>
  
<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
