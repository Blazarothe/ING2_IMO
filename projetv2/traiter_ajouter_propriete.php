<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'administrateur') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = ""; // Remplacez par votre mot de passe
$dbname = "OmnesImmobilier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propriete_id = $_POST['propriete_id'];
    $type_propriete = $_POST['type_propriete'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $dimension = $_POST['dimension'];
    $photo_url = $_POST['photo_url'];
    $agent_id = $_POST['agent_id'];
    $digicode = $_POST['digicode'];

    // Vérifier si l'ID de la propriété existe déjà
    $sql = "SELECT id FROM Proprietes WHERE propriete_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $propriete_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Cet ID de propriété est déjà utilisé.";
        header("Location: ajouter_propriete.php");
        exit();
    } else {
        // Insérer la propriété dans la table Proprietes
        $sql = "INSERT INTO Proprietes (propriete_id, type_propriete, adresse, ville, description, prix, dimension, photo_url, agent_id, digicode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdssis", $propriete_id, $type_propriete, $adresse, $ville, $description, $prix, $dimension, $photo_url, $agent_id, $digicode);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Propriété ajoutée avec succès.";
            header("Location: compte.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de la propriété.";
            header("Location: ajouter_propriete.php");
            exit();
        }
    }

    $stmt->close();
}

$conn->close();
?>
