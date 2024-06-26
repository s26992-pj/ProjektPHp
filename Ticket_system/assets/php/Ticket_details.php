<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$ticket_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Pobranie szczegółów biletu
$sql_ticket = "SELECT * FROM tickets WHERE id = $ticket_id";
$result_ticket = $conn->query($sql_ticket);

if ($result_ticket->num_rows == 1) {
    $ticket = $result_ticket->fetch_assoc();
} else {
    echo "Ticket not found.";
    exit;
}

// Pobranie komentarzy do biletu
$sql_comments = "SELECT comments.*, users.username FROM comments 
                 JOIN users ON comments.user_id = users.id 
                 WHERE ticket_id = $ticket_id ORDER BY created_at ASC";
$result_comments = $conn->query($sql_comments);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ticket Details</title>
    <link rel="stylesheet" href="../css/styleTickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-label">Comments</div>
        <div class="separator"></div>
        <a href="../../add_ticket.php"><i class="fas fa-plus-circle"></i> Add Ticket</a>
        <a href="../../auth/reset_password.php"><i class="fas fa-key"></i> Reset Password</a>
        <a href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main-content">
        <h1>Ticket Details</h1>
        
        <h2><?php echo $ticket['title']; ?></h2>
        <p><strong>Priority:</strong> <?php echo $ticket['priority']; ?></p>
        <p><strong>Department:</strong> <?php echo $ticket['department']; ?></p>
        <p><strong>Status:</strong> <?php echo $ticket['status']; ?></p>
        <p><strong>Added Date:</strong> <?php echo $ticket['added_date']; ?></p>
        <p><strong>Deadline:</strong> <?php echo $ticket['deadline']; ?></p>
        
        <h3>Comments</h3>
        <?php if ($result_comments->num_rows > 0): ?>
            <?php while ($comment = $result_comments->fetch_assoc()): ?>
                <div class="comment">
                    <p><strong><?php echo $comment['username']; ?></strong> <em><?php echo $comment['created_at']; ?></em></p>
                    <p><?php echo nl2br($comment['comment']); ?></p>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <form action="delete_comment.php" method="post">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>
        
        <h3>Add a Comment</h3>
        <form action="add_comment.php" method="post">
            <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
            <textarea name="comment" rows="5" required></textarea><br>
            <button type="submit">Add Comment</button>
        </form>
    </div>
</body>
</html>