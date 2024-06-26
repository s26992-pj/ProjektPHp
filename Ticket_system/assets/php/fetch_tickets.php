<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    echo "unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];

$filterType = $_POST['filterType'];
$filterValue = $_POST['filterValue'];

$sql = "SELECT * FROM tickets WHERE assigned_to = $user_id";

if ($filterType == 'department' && !empty($filterValue)) {
    $sql .= " AND department = '$filterValue'";
} elseif ($filterType == 'priority' && !empty($filterValue)) {
    $sql .= " AND priority = '$filterValue'";
} elseif ($filterType == 'date' && !empty($filterValue)) {
    $sql .= " AND DATE(added_date) = '$filterValue'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table>
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Assigned To</th>
                <th>Added Date</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Action</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td><a href="../assets/php/ticket_details.php?id='.$row['id'].'">'.$row['title'].'</a></td>
                <td>'.$row['priority'].'</td>
                <td>'.$row['department'].'</td>
                <td>'.$row['assigned_to'].'</td>
                <td>'.$row['added_date'].'</td>
                <td>'.$row['deadline'].'</td>
                <td>'.$row['status'].'</td>
                <td>
                    <button onclick="markAsDone('.$row['id'].')">Mark as Done</button>
                </td>
            </tr>';
    }

    echo '</table>';
} else {
    echo 'No tickets found.';
}
?>