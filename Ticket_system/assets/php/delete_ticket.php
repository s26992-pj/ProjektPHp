<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'department_owner' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Rozpocznij transakcję
    $conn->begin_transaction();

    try {
        // Usuń powiązane komentarze
        $sql = "DELETE FROM comments WHERE ticket_id=$ticket_id";
        $conn->query($sql);

        // Usuń bilet
        $sql = "DELETE FROM tickets WHERE id=$ticket_id";
        $conn->query($sql);

        // Zatwierdź transakcję
        $conn->commit();

        if ($_SESSION['role'] == 'admin') {
            header("Location: ../../admin/dashboard.php");
        } else if ($_SESSION['role'] == 'department_owner') {
            header("Location: ../../department_owner/dashboard.php");
        }
        exit;
    } catch (mysqli_sql_exception $exception) {
        // Wycofaj transakcję w przypadku błędu
        $conn->rollback();
        throw $exception;
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