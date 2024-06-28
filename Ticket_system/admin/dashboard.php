<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

//pobieranie danych z ticketów
$sql = "SELECT * FROM tickets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/main.js"></script>
</head>
<body> 
    <div class="navbar">
        <div class="navbar-label">Admin Dashboard</div>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="separator"></div>
        <a href="../add_ticket.php"><i class="fas fa-plus-circle"></i> Add Ticket</a>
        <a href="../assets/php/add_department.php"><i class="fas fa-plus-circle"></i> Add Department</a>
        <a href="../assets/php/delete_department.php"><i class="fas fa-minus-circle"></i> Delete Department</a>
        <a href="../auth/reset_password.php"><i class="fas fa-key"></i> Reset Password</a>
        <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <table>
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Assigned To</th>
                <th>Added Date</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><a href="../assets/php/ticket_details.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
                <td><?php echo $row['priority']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['assigned_to']; ?></td>
                <td><?php echo $row['added_date']; ?></td>
                <td><?php echo $row['deadline']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="../assets/php/edit_ticket.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="../assets/php/delete_ticket.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
