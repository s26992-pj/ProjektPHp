<?php
session_start();
include 'includes/db.php';

$sql = "SELECT * FROM tickets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Tickets</title>
    <link rel="stylesheet" href="assets/css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-label">TicketService</div>
        <a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Log In</a>
        <div class="separator"></div>
        <a href="auth/register.php"><i class="fas fa-user-plus"></i> Sign Up</a>
    </div>
    <div class="main-content">
        <h1>All Tickets</h1>
        <table>
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Added Date</th>
                <th>Deadline</th>
                <th>Status</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['priority']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['added_date']; ?></td>
                <td><?php echo $row['deadline']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>