<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Przekierowanie w zależności od roli użytkownika
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            }
            elseif($_SESSION['role'] === 'user') {
                header("Location: ../user/dashboard.php");
             }
             elseif($_SESSION['role'] === 'department_owner') {
                header("Location: ../department_owner/dashboard.php");
             }
              else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
include '../includes/login.php';
?>

