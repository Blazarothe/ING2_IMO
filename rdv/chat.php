<?php
session_start();

// Connexion à la base de données
include_once "config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    header("Location: ../login/introduction.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}
else{
    header("Location: login.php ");
    exit();
}

