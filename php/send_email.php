<?php
session_start();
include_once "config.php";

header('Content-Type: application/json');

$response = ['status' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['unique_id'];
    $recipient_email = mysqli_real_escape_string($conn, $_POST['recipient']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Fetch recipient ID
    $recipient_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$recipient_email}'");
    if (mysqli_num_rows($recipient_query) > 0) {
        $recipient_row = mysqli_fetch_assoc($recipient_query);
        $recipient_id = $recipient_row['unique_id'];
        
        // Insert email into the database
        $sql = "INSERT INTO emails (incoming_msg_id, outgoing_msg_id, subject, msg) VALUES 
                ({$recipient_id}, {$sender_id}, '{$subject}', '{$message}')";
        if (mysqli_query($conn, $sql)) {
            $response['status'] = 'success';
            $response['message'] = 'Email sent successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to send email.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Recipient not found.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>
