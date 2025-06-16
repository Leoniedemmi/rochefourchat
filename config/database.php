<?php

$host = '192.168.135.113';
$dbname = 'sonzinie';
$username = 'user'; 
$password = 'rQUSxP2xUCxnzU45';     

try {
    $pdo = new PDO('mysql:host=mysql-ijtebowmandevill.mysql.db', 'user', 'pass');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>