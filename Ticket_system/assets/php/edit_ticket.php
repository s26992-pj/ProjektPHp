<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'department_owner' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $priority = $_POST['priority'];
        $department = $_POST['department'];
        $deadline = $_POST['deadline'];
        $status = $_POST['status'];

        $sql = "UPDATE tickets SET title='$title', priority='$priority', department='$department', deadline='$deadline', status='$status' WHERE id=$ticket_id";

        if ($conn->query($sql) === TRUE) {
            if ($_SESSION['role'] == 'admin') {
                header("Location: ../../admin/dashboard.php");
            } else if ($_SESSION['role'] == 'department_owner') {
                header("Location: ../../department_owner/dashboard.php");
            }
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $sql = "SELECT * FROM tickets WHERE id=$ticket_id";
        $result = $conn->query($sql);
        $ticket = $result->fetch_assoc();
    }
} else {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../../admin/dashboard.php");
    } else if ($_SESSION['role'] == 'department_owner') {
        header("Location: ../../department_owner/dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edit Ticket</title>
    <link rel="stylesheet" href="../css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-label">TicketService</div>
        <a href="<?php echo $_SESSION['role'] == 'admin' ? '../../admin/dashboard.php' : '../../department_owner/dashboard.php'; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="separator"></div>
        <a href="../../add_ticket.php"><i class="fas fa-plus-circle"></i> Add Ticket</a>
        <a href="../../auth/reset_password.php"><i class="fas fa-key"></i> Reset Password</a>
        <a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <h1>Edit Ticket</h1>
        <form action="edit_ticket.php?id=<?php echo $ticket_id; ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $ticket['title']; ?>" required>
            <label for="priority">Priority:</label>
            <select id="priority" name="priority" required>
                <option value="low" <?php if ($ticket['priority'] == 'low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if ($ticket['priority'] == 'medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if ($ticket['priority'] == 'high') echo 'selected'; ?>>High</option>
            </select>
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo $ticket['department']; ?>" required>
            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?php echo $ticket['deadline']; ?>" required>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="TO DO" <?php if ($ticket['status'] == 'TO DO') echo 'selected'; ?>>TO DO</option>
                <option value="IN PROGRESS" <?php if ($ticket['status'] == 'IN PROGRESS') echo 'selected'; ?>>IN PROGRESS</option>
                <option value="DONE" <?php if ($ticket['status'] == 'DONE') echo 'selected'; ?>>DONE</option>
            </select>
            <button type="submit">Update Ticket</button>
        </form>
    </div>
</body>
</html>