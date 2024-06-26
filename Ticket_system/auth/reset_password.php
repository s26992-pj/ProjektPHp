<?php
session_start();
include '../includes/db.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Obsługa formularza resetowania hasła
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Sprawdzenie, czy wszystkie pola zostały wypełnione
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Proszę wypełnić wszystkie pola.";
    } else {
        // Pobranie aktualnego hasła z bazy danych
        $sql = "SELECT password FROM users WHERE id = $user_id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            // Sprawdzenie, czy aktualne hasło jest poprawne
            if (password_verify($current_password, $stored_password)) {
                // Sprawdzenie, czy nowe hasło i jego potwierdzenie są identyczne
                if ($new_password == $confirm_password) {
                    // Zaktualizowanie hasła w bazie danych
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

                    if ($conn->query($update_sql) === TRUE) {
                        $success = "Hasło zostało pomyślnie zmienione.";
                        header("Location: login.php");
                    } else {
                        $error = "Błąd podczas aktualizacji hasła: " . $conn->error;
                    }
                } else {
                    $error = "Nowe hasło i potwierdzenie hasła nie są identyczne.";
                }
            } else {
                $error = "Aktualne hasło jest nieprawidłowe.";
            }
        } else {
            $error = "Nie można znaleźć użytkownika.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <h1>Reset Password</h1>

    <?php
    // Wyświetlanie komunikatów błędów lub sukcesu
    if (isset($error)) {
        echo '<div class="error">' . $error . '</div>';
    }
    if (isset($success)) {
        echo '<div class="success">' . $success . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="current_password">Current Password:</label><br>
        <input type="password" id="password" name="current_password" required><br><br>

        <label for="new_password">New Password:</label><br>
        <input type="password" id="password" name="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" id="password" name="confirm_password" required><br><br>

        <input type="submit" value="Reset Password">
    </form>
</body>
</html>