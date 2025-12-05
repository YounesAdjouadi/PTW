<?php
// Fichier : contact/process_contact.php

// AFFICHER LES ERREURS PHP (À désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin corrigé : remonté de contact/ à ft_umbb/, puis dans config/
include('../config/db_config.php'); 

// Log file for contact form errors (ensure logs/ is writable)
$logFile = __DIR__ . '/../logs/contact_errors.log';

// Redirection si l'accès n'est pas via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: contact.html");
    exit();
}

// 1. Récupération et nettoyage des données POST
$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$subject = $conn->real_escape_string($_POST['subject']);
$message = $conn->real_escape_string($_POST['message']);

// 2. Préparation et exécution de la requête
// La requête doit correspondre aux 4 colonnes que nous avons définies dans la table contacts
$sql = "INSERT INTO contacts (full_name, email, subject, message) VALUES (?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // 4 chaînes de caractères : 'ssss'
    $stmt->bind_param("ssss", $full_name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        // Succès
        header("Location: contact.html?status=success");
        exit();
    } else {
        // Échec de l'exécution
        // Log statement error
        error_log("[contact] Execute failed: " . $stmt->error . "\n", 3, $logFile);
        header("Location: contact.html?status=error_logged");
        exit();
    }
    
    $stmt->close();
} else {
    // Échec de la préparation - log error
    error_log("[contact] Prepare failed: " . $conn->error . "\nSQL: " . $sql . "\n", 3, $logFile);
    header("Location: contact.html?status=error_logged");
    exit();
}

$conn->close();
?>