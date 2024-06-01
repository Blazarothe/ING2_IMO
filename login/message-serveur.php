<?php
session_start();
include_once "config.php";

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['unique_id'])) {
    header("Location: ChatApp/login.php");
}

$outgoing_id = $_SESSION['unique_id'];
$server_id = 958768140; 
$message = "Confirmation de reservation du bien immobilier"; //Rajouter une commande SQL pour aller chop l'appartement 

$sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ({$outgoing_id}, {$server_id}, '{$message}')";
if (mysqli_query($conn, $sql)) {
    echo "Message envoyé avec succès.";
} else {
    echo "Erreur lors de l'envoi du message : " . mysqli_error($conn);
}
?>
