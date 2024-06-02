<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; // Remplacez par votre mot de passe
$dbname = "OmnesImmobilier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est administrateur
if ($_SESSION['type_utilisateur'] !== 'administrateur') {
    header("Location: tout_parcourir.php");
    exit();
}

if (isset($_POST['propriete_id'])) {
    $proprieteId = $_POST['propriete_id'];

    // Supprimer la propriété de la base de données
    $sql = "DELETE FROM Proprietes WHERE propriete_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $proprieteId);
    $stmt->execute();

    // Vérifier si la suppression a réussi
    if ($stmt->affected_rows > 0) {
        echo "Propriété supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression de la propriété.";
    }

    $stmt->close();
}

$conn->close();
header("Location: tout_parcourir.php");
exit();
?>
