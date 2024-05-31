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

$error = "";

// Vérification des informations de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT user_id, password, type_utilisateur FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($mot_de_passe === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['type_utilisateur'] = $user['type_utilisateur'];
            header("Location: compte.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Email non trouvé.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Omnes Immobilier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .login-form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-form button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .login-form .signup-btn {
            display: block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Omnes Immobilier</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="tout_parcourir.php">Tout Parcourir</a></li>
                <li><a href="rechercher.php">Recherche</a></li>
                <li><a href="rendez_vous.php">Rendez-vous</a></li>
                <li><a href="compte.php">Votre Compte</a></li>
                <li><a href="chat.php">Chat</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Connexion</h2>
        <div class="login-form">
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>

                <button type="submit">Se connecter</button>
            </form>
            <a class="signup-btn" href="../login/login.php">Créer un compte</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>
