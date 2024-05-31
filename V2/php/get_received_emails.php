<?php
session_start();
include_once "config.php";

$outgoing_id = $_SESSION['unique_id'];
$sql = "SELECT * FROM emails WHERE incoming_msg_id = {$outgoing_id} ORDER BY email_id DESC";
$query = mysqli_query($conn, $sql);
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "<p>No emails found.</p>";
} else {
    while ($row = mysqli_fetch_assoc($query)) {
        // Fetch sender's information
        $sender_sql = mysqli_query($conn, "SELECT fname, lname FROM users WHERE unique_id = {$row['outgoing_msg_id']}");
        $sender_row = mysqli_fetch_assoc($sender_sql);
        $sender_name = $sender_row['fname'] . ' ' . $sender_row['lname'];
        
        $output .= '
        <div class="email-item">
            <span class="email-sender">' . htmlspecialchars($sender_name, ENT_QUOTES, 'UTF-8') . '</span>
            <span class="email-subject">' . htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8') . '</span>
        </div>';
    }
}
echo $output;
?>
