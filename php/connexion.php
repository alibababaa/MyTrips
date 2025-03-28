<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    // Exemple de validation (à remplacer par une vraie vérification de BD)
    // On simule ici un unique utilisateur
    if ($email === 'utilisateur@example.com' && $password === 'motdepasse') {
        // On stocke l'utilisateur en session sous forme de tableau
        $_SESSION['user'] = [
            'login'  => $email,
            'prenom' => 'Jean',  // exemple
            'role'   => 'user'
        ];
        header('Location: accueil.php');
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet"/>
</head>
<body>
  
<!-- Navigation -->
<nav>
  <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"/></div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="presentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>

    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else:
