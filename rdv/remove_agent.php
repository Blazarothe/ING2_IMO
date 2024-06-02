<?php
include 'config.php'; // Database configuration for the first database

// Connect to the second database
$second_db_host = 'localhost';
$second_db_user = 'root';
$second_db_password = '';
$second_db_name = 'reseau'; // The second database name

$second_conn = new mysqli($second_db_host, $second_db_user, $second_db_password, $second_db_name);

// Check connection
if ($second_conn->connect_error) {
    die("Connection to second database failed: " . $second_conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $agent_email = $_POST['agent_email'];

    // Remove agent from the first database (agents table)
    $sql1 = "DELETE agents FROM agents 
             JOIN utilisateurs ON agents.utilisateur_id = utilisateurs.id
             WHERE utilisateurs.email = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $agent_email);

    // Remove user from the utilisateurs table in the first database
    $sql2 = "DELETE FROM utilisateurs WHERE email = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $agent_email);

    // Remove user from the users table in the second database
    $sql3 = "DELETE FROM users WHERE email = ?";
    $stmt3 = $second_conn->prepare($sql3);
    $stmt3->bind_param("s", $agent_email);

    if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute()) {
        header("Location: agent_list.php");
        exit();
    } else {
        echo "erreur retrait agent " . $conn->error . " / " . $second_conn->error;
    }

    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
}

$conn->close();
$second_conn->close();
?>
