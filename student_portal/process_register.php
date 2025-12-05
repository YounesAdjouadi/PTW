<?php
// Fichier : student_portal/process_register.php

// AFFICHER LES ERREURS PHP (À désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin corrigé pour inclure le fichier de configuration de la DB
include('../config/db_config.php'); 

// Vérifiez si la connexion a échoué (l'objet $conn est défini dans db_config.php)
if (!isset($conn) || $conn->connect_error) {
    die("Erreur : La connexion à la DB n'a pas été établie.");
}

// Redirection si l'accès n'est pas via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php");
    exit();
}

// 1. Récupération et nettoyage des données POST
$matricule = $conn->real_escape_string($_POST['matricule']);
$email = $conn->real_escape_string($_POST['email']);
$fullname = $conn->real_escape_string($_POST['fullname']);
$department = $conn->real_escape_string($_POST['department']);
$password_input = $_POST['password']; 

// Validation (optionnel)
if (!preg_match('/^[0-9]{12}$/', $matricule)) {
    header("Location: register.php?status=error&msg=Invalid_matricule_format");
    exit();
}

// 2. Hashage du mot de passe
$hashed_password = MD5($password_input);


// 3. Préparer l'insertion
// La requête doit correspondre aux 5 colonnes que nous avons définies dans la table students
$sql = "INSERT INTO students (matricule, email, fullname, password, department) VALUES (?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // Lie les paramètres (5 chaînes de caractères : 'sssss')
    $stmt->bind_param("sssss", $matricule, $email, $fullname, $hashed_password, $department);
    
    if ($stmt->execute()) {
        // Insertion réussie
        header("Location: register.php?status=success");
        exit();
    } else {
        // Erreur d'exécution de la requête
        
        // Code d'erreur 1062 = Duplicata (Matricule ou Email déjà existant)
        if ($conn->errno == 1062) {
            header("Location: register.php?status=exists");
            exit();
        } else {
            // Autres erreurs SQL
            header("Location: register.php?status=error");
            // Pour le débogage, vous pouvez utiliser : die("Erreur SQL: " . $stmt->error);
            exit();
        }
    }
    
    $stmt->close();
} else {
    // Erreur de préparation de la requête
    header("Location: register.php?status=error");
    exit();
}

$conn->close();
?>