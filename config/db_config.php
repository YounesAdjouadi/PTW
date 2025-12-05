<?php
// Fichier : config/db_config.php

// Paramètres de connexion à la base de données
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');     
define('DB_NAME', 'ft_umbb_db'); 

// Connexion à la base de données MySQL
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    // En cas d'échec de la connexion, affiche un message d'erreur fatal
    die("ERREUR FATALE: Échec de la connexion à la base de données. " . $conn->connect_error);
}

// Définir le jeu de caractères pour les accents
$conn->set_charset("utf8mb4");

// L'objet de connexion $conn est maintenant prêt à être utilisé par les autres scripts
?>