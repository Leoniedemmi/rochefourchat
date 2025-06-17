<?php
// Configuration pour OVH - remplacez par vos vraies informations OVH
$host = 'ijtebowmandevill.mysql.db'; // Ex: mysql51-66.pro ou cluster015.hosting.ovh.net
$dbname = 'ijtebowmandevill';
$username = 'ijtebowmandevill'; // Votre nom d'utilisateur OVH
$password = 'LeonieMANDEVILLE2025';

try {
    // Correction de la variable $host dans la chaîne de connexion
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>