<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'administrateur') {
    header("Location: login.php");
    exit;
}

include_once "config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $telephone = $_POST['telephone'];
    $cv = $_POST['cv'];
    $disponibilite = $_POST['disponibilite'];

    // Vérifier si l'email existe déjà
    $sql = "SELECT id FROM Utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header("Location: ajouter_agent.php");
        exit();
    } else {
        // Insérer l'utilisateur dans la table Utilisateurs
        $hashedPassword = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $sql = "INSERT INTO Utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, 'agent')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $email, $hashedPassword);
        if ($stmt->execute()) {
            $utilisateur_id = $stmt->insert_id;
            // Insérer l'agent dans la table Agents
            $sql = "INSERT INTO Agents (utilisateur_id, telephone, cv, disponibilite) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $utilisateur_id, $telephone, $cv, $disponibilite);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Agent ajouté avec succès.";
                header("Location: compte.php");
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout de l'agent.";
                header("Location: ajouter_agent.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur.";
            header("Location: ajouter_agent.php");
            exit();
        }
    }

    $stmt->close();
}

$conn->close();
?>

