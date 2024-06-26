<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tickets WHERE assigned_to = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>My Tickets</title>
    <link rel="stylesheet" href="../assets/css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h1>My Tickets</h1>
    <table>
        <tr>
            <th>Title</th>
            <th>Priority</th>
            <th>Department</th>
            <th>Added Date</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['priority']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['added_date']; ?></td>
            <td><?php echo $row['deadline']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <?php if ($row['status'] == 'open'): ?>
                <a href="close_ticket.php?id=<?php echo $row['id']; ?>">Mark as Closed</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>