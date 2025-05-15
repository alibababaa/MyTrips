<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Non connecté']);
    exit;
}

$champ = $_POST['champ'] ?? '';
$valeur = $_POST['valeur'] ?? '';
$login = $_SESSION['user']['login'];

$champsAutorises = ['login', 'name', 'prenom', 'email'];
if (!in_array($champ, $champsAutorises)) {
    echo json_encode(['success' => false, 'error' => 'Champ invalide']);
    exit;
}

$usersFile = 'users.json';
$users = json_decode(file_get_contents($usersFile), true);
$modifOK = false;

foreach ($users as &$user) {
    if ($user['login'] === $login) {
        $user[$champ] = $valeur;
        $_SESSION['user'][$champ] = $valeur;
        $modifOK = true;
        break;
    }
}

if ($modifOK) {
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);
}
