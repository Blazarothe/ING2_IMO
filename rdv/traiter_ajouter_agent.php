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
    $cv_url = $_POST['cv_url'];
    $photo_profil_url = $_POST['photo_profil_url'];
    $disponibilite = $_POST['disponibilite'];

    // Vérifier si l'email existe déjà dans la première base de données
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
        // Hacher le mot de passe avant de l'insérer
        $hashedPassword = $mot_de_passe;
        // Insérer l'utilisateur dans la table Utilisateurs
        $sql = "INSERT INTO Utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, 'agent')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $email, $hashedPassword);
        if ($stmt->execute()) {
            $utilisateur_id = $stmt->insert_id;
            // Insérer l'agent dans la table Agents
            $sql = "INSERT INTO Agents (utilisateur_id, telephone, cv, disponibilite, photo_profil_url) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $utilisateur_id, $telephone, $cv_url, $disponibilite, $photo_profil_url);
            if ($stmt->execute()) {
                // Deuxième connexion à la base de données
                $dbname2 = "reseau";
                $conn2 = mysqli_connect($hostname, $username, $password, $dbname2);

                if (!$conn2) {
                    $_SESSION['error'] = "Erreur de connexion à la deuxième base de données.";
                    header("Location: ajouter_agent.php");
                    exit();
                }

                $fname = mysqli_real_escape_string($conn2, $_POST['prenom']);
                $lname = mysqli_real_escape_string($conn2, $_POST['nom']);
                $email2 = mysqli_real_escape_string($conn2, $_POST['email']);
                $password2 = mysqli_real_escape_string($conn2, $_POST['mot_de_passe']);

                if (!empty($fname) && !empty($lname) && !empty($email2) && !empty($password2)) {
                    if (filter_var($email2, FILTER_VALIDATE_EMAIL)) {
                        $sql2 = mysqli_query($conn2, "SELECT * FROM users WHERE email = '{$email2}'");
                        if (mysqli_num_rows($sql2) > 0) {
                            $_SESSION['error'] = "$email2 - This email already exists!";
                            header("Location: ajouter_agent.php");
                            exit();
                        } else {
                            $ran_id = rand(time(), 100000000);
                            $status = "Active now";
                            $role = '2';
                            // Hacher le mot de passe avant de l'insérer dans la deuxième base de données
                            $encrypt_pass = md5($password2);
                            $insert_query = mysqli_query($conn2, "INSERT INTO users (unique_id, fname, lname, email, password, status, role) VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email2}', '{$encrypt_pass}', '{$status}', '{$role}')");
                            if ($insert_query) {
                                $_SESSION['message'] = "Agent ajouté avec succès.";
                                header("Location: compte.php");
                                exit();
                            } else {
                                $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur dans la deuxième base de données.";
                                header("Location: ajouter_agent.php");
                                exit();
                            }
                        }
                    } else {
                        $_SESSION['error'] = "$email2 is not a valid email!";
                        header("Location: ajouter_agent.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "All input fields are required!";
                    header("Location: ajouter_agent.php");
                    exit();
                }
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

