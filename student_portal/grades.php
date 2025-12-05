<?php
// student_portal/grades.php
// Display logged-in student's grades. Average = cc*0.4 + exam*0.6

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header('Location: login.html');
    exit();
}

require_once __DIR__ . '/../config/db_config.php'; // provides $conn

$matricule = $_SESSION['matricule'] ?? '';
if ($matricule === '') {
    echo "Student identifier missing. Please log in again.";
    exit();
}

    $sql = "SELECT `module`, `cc`, `exam`, `average`, `term`
        FROM `grades`
        WHERE `matricule` = ?
        ORDER BY `term` DESC, `module` ASC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log('Prepare failed (grades): ' . $conn->error);
    echo 'Internal error.';
    exit();
}
$stmt->bind_param('s', $matricule);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Grades</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="padding:20px;">
        <a href="dashboard.php">&larr; Back to Dashboard</a>
        <h1>My Grades</h1>
        <p>Student: <?php echo htmlspecialchars($_SESSION['fullname'] ?? ''); ?> — Matricule: <?php echo htmlspecialchars($matricule); ?></p>

        <?php if ($result && $result->num_rows > 0): ?>
            <table style="width:100%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th>Term</th>
                        <th>Module</th>
                        <th>CC</th>
                        <th>Exam</th>
                        <th>Average (40% CC + 60% Exam)</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="text-align:center"><?php echo htmlspecialchars($row['term']); ?></td>
                        <td><?php echo htmlspecialchars($row['module']); ?></td>
                        <td style="text-align:center"><?php echo number_format((float)$row['cc'],2); ?></td>
                        <td style="text-align:center"><?php echo number_format((float)$row['exam'],2); ?></td>
                        <td style="text-align:center"><?php echo number_format((float)$row['average'],2); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No grades found for your account.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
