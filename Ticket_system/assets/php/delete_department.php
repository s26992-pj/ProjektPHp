<?php
// Włączenie pliku z konfiguracją połączenia do bazy danych
include '../../includes/db.php';
// Rozpoczęcie sesji PHP
session_start();

// Sprawdzenie, czy użytkownik jest administratorem
if ($_SESSION['role'] != 'admin') {
    // Jeśli nie jest, przekierowanie do strony logowania
    header("Location: ../../auth/login.php");
    exit;
}

// Funkcja usuwająca dział na podstawie jego ID
function deleteDepartment($conn, $department_id) {
    // Zapytanie SQL do usunięcia działu
    $delete_query = "DELETE FROM departments WHERE id = $department_id";

    // Wykonanie zapytania i zwrócenie wyniku operacji
    if ($conn->query($delete_query) === TRUE) {
        return true; // Usunięcie powiodło się
    } else {
        return false; // Błąd podczas usuwania działu
    }
}

// Obsługa usuwania działu, jeśli ID jest przekazane przez parametr GET
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    // Próba usunięcia działu i ustawienie komunikatu sesji
    if (deleteDepartment($conn, $department_id)) {
        $_SESSION['message'] = "Department deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting department: " . $conn->error;
    }

    // Przekierowanie w celu wyczyszczenia parametru GET
    header("Location: delete_department.php");
    exit;
}

// Pobranie wszystkich działów z bazy danych
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Delete Department</title>
    <!-- Dołączenie stylów CSS -->
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
        <!-- Wyświetlenie komunikatu sesji, jeśli istnieje -->
        <?php if (isset($_SESSION['message'])): ?>
            <p><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); // Usunięcie komunikatu sesji po jego wyświetleniu ?>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iteracja przez wszystkie działy i wyświetlenie ich w tabeli -->
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <!-- Link do usunięcia działu, z potwierdzeniem -->
                            <a href="?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>