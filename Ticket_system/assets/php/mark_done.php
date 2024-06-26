<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    echo "unauthorized";
    exit;
}

if (isset($_POST['id'])) {
    $ticket_id = $_POST['id'];
    
    // Update the status of the ticket to 'DONE'
    $sql = "UPDATE tickets SET status = 'DONE' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ticket_id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "no_id";
}
?>