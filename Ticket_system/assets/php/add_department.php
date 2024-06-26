<?php
include '../../includes/db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newDepartment = $_POST['department']; // Fetch the new department value from the form

    // Validate and sanitize input (optional step)

    // Check if department already exists in departments table
    $check_query = "SELECT * FROM departments WHERE name = '$newDepartment'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo "Department already exists.";
    } else {
        // Insert new department into departments table
        $insert_query = "INSERT INTO departments (name) VALUES ('$newDepartment')";

        if ($conn->query($insert_query) === TRUE) {
            echo "New department added successfully.";
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Add Department</title>
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
        <h1>Add Department</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="department">Department Name:</label>
            <input type="text" id="department" name="department" required>
            <button type="submit">Add Department</button>
        </form>
    </div>
</body>
</html>