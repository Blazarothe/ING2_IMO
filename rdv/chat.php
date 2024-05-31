<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Alex2201"; // Remplacez par votre mot de passe
$dbname = "OmnesImmobilier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    header("Location: ../login/chat.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}
else{
    header("Location: login.php ");
    exit();
}

