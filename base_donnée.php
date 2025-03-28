<?php
/**
 * base_donnee.php
 * Connexion à la base de données via PDO.
 * Vous pouvez inclure ce fichier là où vous en avez besoin.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'nom_de_ta_base';  // À adapter
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Options pour activer les exceptions et une gestion stricte
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
