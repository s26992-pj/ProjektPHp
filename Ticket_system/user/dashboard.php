<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/main.js"></script>
</head>
<body>
    <div class="navbar">
        <div class="navbar-label">UserDashboard</div>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="separator"></div>
        <a href="../add_ticket.php"><i class="fas fa-plus-circle"></i> Add Ticket</a>
        <a href="../auth/reset_password.php"><i class="fas fa-key"></i> Reset Password</a>
        <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <div>
            <label for="filter">Filter by:</label>
            <select id="filter" onchange="filterTickets()">
                <option value="all">All</option>
                <option value="department">Department</option>
                <option value="priority">Priority</option>
                <option value="date">Date</option>
            </select>
        </div>
        <div id="ticket-table">
            <!-- Tickets will be loaded here via AJAX -->
        </div>
    </div>
</body>
</html>