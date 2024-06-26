<?php
include '../../includes/db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

// Function to delete department by ID
function deleteDepartment($conn, $department_id) {
    $delete_query = "DELETE FROM departments WHERE id = $department_id";

    if ($conn->query($delete_query) === TRUE) {
        return true; // Deleted successfully
    } else {
        return false; // Error deleting department
    }
}

// Handle department deletion if ID is provided via GET parameter
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    if (deleteDepartment($conn, $department_id)) {
        $_SESSION['message'] = "Department deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting department: " . $conn->error;
    }

    header("Location: delete_department.php"); // Redirect to clear GET parameter
    exit;
}

// Fetch all departments
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Delete Department</title>
    <link rel="stylesheet" href="../css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-label">Admin Dashboard</div>
        <a href="../../admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="separator"></div>
        <a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <h1>Delete Department</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <p><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>