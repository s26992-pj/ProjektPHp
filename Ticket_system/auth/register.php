<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Dodane pole role

    // Dodatkowa walidacja, na przykład sprawdzenie, czy rola jest prawidłowa
    $valid_roles = ['admin', 'user','department_owner']; // Dopuszczalne role

    if (!in_array($role, $valid_roles)) {
        echo "Invalid role selected.";
        exit;
    }

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin">Administrator</option>
            <option value="user">Użytkownik</option>
        </select>
        <button type="submit">Register</button>
        <a href="Login.php" class="Login_button">Log In</a>
    </form>
</body>
</html>