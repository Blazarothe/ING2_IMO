<?php
include_once "config.php";

$outgoing_id = $_SESSION['unique_id'];
$sql = "SELECT * FROM emails WHERE outgoing_msg_id = {$outgoing_id} ORDER BY email_id DESC";
$query = mysqli_query($conn, $sql);
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "<p>No emails found.</p>";
} else {
    while ($row = mysqli_fetch_assoc($query)) {
        // Fetch recipient's information
        $recipient_sql = mysqli_query($conn, "SELECT fname, lname FROM users WHERE unique_id = {$row['incoming_msg_id']}");
        $recipient_row = mysqli_fetch_assoc($recipient_sql);
        $recipient_name = $recipient_row['fname'] . ' ' . $recipient_row['lname'];
        
        $output .= '
        <div class="email-item">
            <span class="email-recipient">' . htmlspecialchars($recipient_name, ENT_QUOTES, 'UTF-8') . '</span>
            <span class="email-subject">' . htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8') . '</span>
            <span class="email-date">' . '</span>
        </div>';
    }
}
echo $output;
?>
