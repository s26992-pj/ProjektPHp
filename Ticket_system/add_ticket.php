<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $department = $_POST['department'];
    $deadline = $_POST['deadline'];
    $assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : NULL;

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $attachment = basename($_FILES['attachment']['name']);
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . $attachment;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_file)) {
            // File successfully uploaded
        } else {
            // Handle error
            echo "Error uploading file.";
            exit;
        }
    } else {
        $attachment = NULL;
    }

    // Insert ticket into database
    $sql = "INSERT INTO tickets (title, priority, department, deadline, assigned_to, attachment) 
            VALUES ('$title', '$priority', '$department', '$deadline', '$assigned_to', '$attachment')";

    if ($conn->query($sql) === TRUE) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        }
        elseif($_SESSION['role'] === 'user') {
            header("Location: user/dashboard.php");
         }
         elseif($_SESSION['role'] === 'department_owner') {
            header("Location: department_owner/dashboard.php");
         }
          else {
            header("Location: index.php");
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Add Ticket</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <form action="add_ticket.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="priority">Priority:</label>
        <select id="priority" name="priority" required>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" required>
        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" required>
        <label for="assigned_to">Assigned To (User ID):</label>
        <input type="number" id="assigned_to" name="assigned_to">
        <label for="attachment">Attachment:</label>
        <input type="file" id="attachment" name="attachment">
        <button type="submit">Add Ticket</button>
    </form>
</body>
</html>