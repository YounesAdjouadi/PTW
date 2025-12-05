<?php
// student_portal/request_transcript.php
// Submit a transcript request and list previous requests for the logged-in student.

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header('Location: login.html');
    exit();
}

require_once __DIR__ . '/../config/db_config.php';

$matricule = $_SESSION['matricule'] ?? '';
$fullname = $_SESSION['fullname'] ?? '';
$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = trim($_POST['note'] ?? '');

    if ($matricule === '') {
        $errors[] = 'Student identifier missing in session.';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO `transcript_requests` (`student_matricule`, `note`, `status`) VALUES (?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log('Prepare failed (request): ' . $conn->error);
            $errors[] = 'Internal error.';
        } else {
            $stmt->bind_param('ss', $matricule, $note);
            if ($stmt->execute()) {
                $success = 'Your transcript request has been submitted. Request ID: ' . $stmt->insert_id;
            } else {
                error_log('Execute failed (request): ' . $stmt->error);
                $errors[] = 'Failed to submit your request.';
            }
            $stmt->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Request Transcript</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="padding:20px;">
        <a href="dashboard.php">&larr; Back to Dashboard</a>
        <h1>Request Transcript</h1>
        <p>Student: <?php echo htmlspecialchars($fullname); ?> — Matricule: <?php echo htmlspecialchars($matricule); ?></p>

        <?php if ($success): ?>
            <div style="padding:10px; border:1px solid #4CAF50; background:#e8f5e9; color:#2e7d32;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="padding:10px; border:1px solid #f44336; background:#ffebee; color:#b71c1c;">
                <ul style="margin:0; padding-left:1.2em;">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="request_transcript.php" style="margin-top:15px; max-width:700px;">
            <label for="note">Note / Purpose (optional)</label><br/>
            <textarea id="note" name="note" rows="5" style="width:100%;"><?php echo htmlspecialchars($_POST['note'] ?? ''); ?></textarea>
            <br/><br/>
            <button type="submit" style="padding:10px 16px;">Submit Request</button>
        </form>

        <hr/>
        <h3>Your previous requests</h3>
        <?php
            $listSql = "SELECT id, note, status, requested_at FROM transcript_requests WHERE student_matricule = ? ORDER BY requested_at DESC LIMIT 20";
            $listStmt = $conn->prepare($listSql);
            if ($listStmt) {
                $listStmt->bind_param('s', $matricule);
                $listStmt->execute();
                $listRes = $listStmt->get_result();
                if ($listRes->num_rows > 0) {
                    echo '<table border="1" style="width:100%; border-collapse:collapse;"><thead><tr><th>ID</th><th>Requested At</th><th>Status</th><th>Note</th></tr></thead><tbody>';
                    while ($r = $listRes->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td style="text-align:center;">' . htmlspecialchars($r['id']) . '</td>';
                        echo '<td style="text-align:center;">' . htmlspecialchars($r['requested_at']) . '</td>';
                        echo '<td style="text-align:center;">' . htmlspecialchars($r['status']) . '</td>';
                        echo '<td>' . nl2br(htmlspecialchars($r['note'])) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No previous requests found.</p>';
                }
                $listStmt->close();
            } else {
                error_log('Prepare failed (list requests): ' . $conn->error);
            }

            $conn->close();
        ?>
    </div>
</body>
</html>
