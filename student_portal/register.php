<?php
// Fichier : student_portal/register.php
// Nous incluons ce fichier PHP pour pouvoir afficher les messages de statut (success/error)

// Styles et entête HTML... (utilisez le style de register.html sans darkmode)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration | Faculty of Technology</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles spécifiques (simplifiés, le reste est dans style.css) */
        body {
            background-color: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .register-container {
            width: 100%;
            max-width: 550px;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow-medium);
            text-align: center;
        }
        .register-container h1 { color: var(--secondary); font-size: 2rem; }
        .register-container p.subtitle { border-bottom: 2px solid var(--primary); padding-bottom: 15px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group input, .form-group select {
            width: 100%; padding: 12px 15px; border: 1px solid var(--border-color);
            border-radius: 6px; box-sizing: border-box; font-size: 1em;
        }
        .register-btn {
            width: 100%; background-color: var(--primary); color: white; border: none;
            padding: 15px; border-radius: 6px; cursor: pointer; font-size: 1.1em;
        }
        .status-message {
            padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: 600;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="register-container">
        <img src="../assets/img/logo-ft.png" alt="Faculty Logo" style="height: 50px; margin-bottom: 20px;">
        <h1>Student Registration</h1>
        <p class="subtitle">Complete this form to create your student portal account.</p>

        <?php 
            // Afficher le message de statut si présent dans l'URL
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo '<div class="status-message success"><i class="fas fa-check-circle"></i> Account created successfully! You can now <a href="login.html">login</a>.</div>';
                } elseif ($_GET['status'] == 'exists') {
                    echo '<div class="status-message error"><i class="fas fa-exclamation-triangle"></i> Error: The Matricule or Email is already registered.</div>';
                } elseif ($_GET['status'] == 'error') {
                    echo '<div class="status-message error"><i class="fas fa-times-circle"></i> An error occurred during registration. Please try again.</div>';
                }
            }
        ?>

        

        <p style="margin-top: 20px; font-size: 0.9em;">
            <a href="login.html" style="color: var(--secondary); font-weight: 600;">Already have an account? Log In</a>
        </p>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>