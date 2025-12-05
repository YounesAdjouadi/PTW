<?php
// Démarrer la session et vérifier l'authentification
session_start();

// Vérification de sécurité
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    // Si l'utilisateur n'est pas connecté, le renvoyer à la page de connexion
    header("Location: login.html");
    exit();
}

// Récupération des données de l'utilisateur stockées dans la session
$fullname = $_SESSION['fullname'];
$matricule = $_SESSION['matricule'];
$department = $_SESSION['department'];

// Script de déconnexion
// Vous pouvez le mettre dans un fichier à part (logout.php) ou ici.
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Student Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles spécifiques au Tableau de Bord */
        .dashboard-header {
            background-color: var(--primary);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .welcome-message {
            font-size: 1.8rem;
            font-weight: 500;
        }
        .user-info {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .logout-link {
            color: white;
            font-weight: 600;
            text-decoration: none;
            float: right;
            margin-top: 10px;
        }
        .logout-link:hover {
            color: var(--secondary);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .dashboard-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            border-left: 5px solid var(--accent);
            transition: transform 0.2s;
            text-decoration: none;
            color: var(--text-dark);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-left-color: var(--secondary);
        }
        .dashboard-card i {
            font-size: 2.5em;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .dashboard-card h3 {
            font-size: 1.3rem;
            margin: 0;
        }
        .dashboard-card p {
            font-size: 0.9em;
            color: var(--text-light);
        }
        
        /* DARK MODE Adjustments */
        .dark-mode .dashboard-card {
            background: #262626;
            box-shadow: none;
            border: 1px solid #3d3d3d;
        }
    </style>
</head>
<body>
    
    <header class="dashboard-header">
        <div class="container">
            <a href="?logout=true" class="logout-link"><i class="fas fa-sign-out-alt"></i> Log Out</a>
            <p class="welcome-message">Welcome, <?php echo htmlspecialchars($fullname); ?>!</p>
            <p class="user-info">
                Matricule: <?php echo htmlspecialchars($matricule); ?> | Department: <?php echo htmlspecialchars($department); ?>
            </p>
        </div>
    </header>

    <main class="container">
        
        <h2><i class="fas fa-th-large"></i> Dashboard Overview</h2>

        <div class="dashboard-grid">
            
            <a href="grades.php" class="dashboard-card" style="border-left-color: #5cb85c;">
                <i class="fas fa-chart-line" style="color: #5cb85c;"></i>
                <h3>My Grades</h3>
                <p>Consult your official semester and yearly results.</p>
            </a>
            
            <a href="../schooling/TimeTable.html" class="dashboard-card" style="border-left-color: #f0ad4e;">
                <i class="fas fa-calendar-alt" style="color: #f0ad4e;"></i>
                <h3>Weekly Schedule</h3>
                <p>View the up-to-date timetable for your level and group.</p>
            </a>
            
            <a href="../at_home_learning/Elearning.html" class="dashboard-card" style="border-left-color: #008cba;">
                <i class="fas fa-book-open" style="color: #008cba;"></i>
                <h3>Course Materials</h3>
                <p>Direct link to the E-learning platform for course content.</p>
            </a>
            
            <a href="request_transcript.php" class="dashboard-card" style="border-left-color: #d9534f;">
                <i class="fas fa-file-invoice" style="color: #d9534f;"></i>
                <h3>Request Transcript</h3>
                <p>Submit an online request for an academic transcript.</p>
            </a>
            
        </div>
        
    </main>

    <footer style="margin-top: 50px; background-color: var(--bg-light); border-top: 1px solid var(--border-color);">
        <div class="container">
            <p style="text-align: center; color: var(--text-light); margin: 10px 0;">© 2025 Student Portal - Faculty of Technology.</p>
        </div>
    </footer>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>