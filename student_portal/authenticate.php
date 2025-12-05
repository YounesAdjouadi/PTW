<?php
// Démarrer la session (IMPORTANT : doit être la première chose)
session_start();

// Inclure le fichier de connexion à la base de données
include('../config\db_config.php'); 

// Redirection si l'accès n'est pas via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.html");
    exit();
}

// 1. Récupération des données POST
$matricule = $conn->real_escape_string($_POST['matricule']);
$password_input = $conn->real_escape_string($_POST['password']); 

// 2. Hashage du mot de passe entré par l'utilisateur (doit correspondre à la méthode de stockage)
// ATTENTION : Pour cet exemple, nous utilisons MD5 simple, car nous l'avons utilisé pour l'insertion de test.
// En production, il faut utiliser password_hash() et password_verify() (plus sécurisé).
$hashed_password_input = MD5($password_input);


// 3. Préparer et exécuter la requête de vérification
$sql = "SELECT matricule, fullname, department FROM students WHERE matricule = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $matricule, $hashed_password_input); // 'ss' pour deux chaînes de caractères
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // CONNEXION RÉUSSIE
    $user = $result->fetch_assoc();
    
    // Enregistrement des données de l'utilisateur dans la session
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['matricule'] = $user['matricule'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['department'] = $user['department'];
    
    // Redirection vers le tableau de bord
    header("Location: dashboard.php");
    exit();
} else {
    // ÉCHEC DE LA CONNEXION
    header("Location: login.html?login=failed");
    exit();
}

$stmt->close();
$conn->close();
?>