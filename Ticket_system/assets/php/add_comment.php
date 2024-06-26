<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = intval($_POST['ticket_id']);
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    if (!empty($comment)) {
        $sql = "INSERT INTO comments (ticket_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $ticket_id, $user_id, $comment);

        if ($stmt->execute()) {
            header("Location: ticket_details.php?id=$ticket_id");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Comment cannot be empty.";
    }
} else {
    header("Location: ../dashboard.php");
    exit;
}
?>