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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

$userId = $_SESSION['user_id'];
$typeUtilisateur = isset($_SESSION['type_utilisateur']) ? $_SESSION['type_utilisateur'] : '';

// Mettre à jour les informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Hachage du mot de passe
    $hashedPassword = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    $updateSql = "UPDATE Utilisateurs SET nom=?, prenom=?, email=?, mot_de_passe=? WHERE id=?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssi", $nom, $prenom, $email, $hashedPassword, $userId);
    $stmt->execute();
}

// Prendre un rendez-vous (uniquement pour les clients)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['take_rendezvous']) && $typeUtilisateur === 'client') {
    $propriete_id = $_POST['propriete_id'];
    $agent_id = $_POST['agent_id'];
    $date_heure = $_POST['date_heure'];
    $insertSql = "INSERT INTO RendezVous (client_id, agent_id, propriete_id, date_heure, statut) VALUES (?, ?, ?, ?, 'confirmé')";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iiss", $userId, $agent_id, $propriete_id, $date_heure);
    $stmt->execute();
}

// Récupérer les informations de l'utilisateur
$sql = "SELECT nom, prenom, email FROM Utilisateurs WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Récupérer les propriétés pour le formulaire de prise de rendez-vous (uniquement pour les clients)
if ($typeUtilisateur === 'client') {
    $proprieteSql = "SELECT propriete_id, adresse FROM Proprietes";
    $proprieteResult = $conn->query($proprieteSql);

    // Récupérer les agents immobiliers
    $agentSql = "SELECT a.id, u.nom, u.prenom FROM Agents a JOIN Utilisateurs u ON a.utilisateur_id = u.id";
    $agentResult = $conn->query($agentSql);
}

// Récupérer les rendez-vous pour l'utilisateur ou l'agent
$rdvSql = ($typeUtilisateur == 'client') ? 
    "SELECT rv.date_heure, p.adresse, ua.nom AS agent_nom, ua.prenom AS agent_prenom, uc.nom AS client_nom, uc.prenom AS client_prenom 
     FROM RendezVous rv 
     JOIN Proprietes p ON rv.propriete_id = p.propriete_id 
     JOIN Agents a ON rv.agent_id = a.id
     JOIN Utilisateurs ua ON a.utilisateur_id = ua.id
     JOIN Utilisateurs uc ON rv.client_id = uc.id
     WHERE rv.client_id = ? AND rv.statut = 'confirmé'" :
    "SELECT rv.date_heure, p.adresse, ua.nom AS agent_nom, ua.prenom AS agent_prenom, uc.nom AS client_nom, uc.prenom AS client_prenom 
     FROM RendezVous rv 
     JOIN Proprietes p ON rv.propriete_id = p.propriete_id 
     JOIN Agents a ON rv.agent_id = a.id
     JOIN Utilisateurs ua ON a.utilisateur_id = ua.id
     JOIN Utilisateurs uc ON rv.client_id = uc.id
     WHERE a.utilisateur_id = ? AND rv.statut = 'confirmé'";

$stmt = $conn->prepare($rdvSql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$rdvResult = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Compte - Omnes Immobilier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            flex: 1;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .rendezvous-list {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rendezvous-item {
            margin-bottom: 20px;
        }
        .rendezvous-item h3 {
            margin: 0;
            color: #333;
        }
        .rendezvous-item p {
            margin: 5px 0;
            color: #666;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        .logout-button {
            margin-top: 20px;
            text-align: center;
        }
        .logout-button form {
            display: inline;
        }
        .logout-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        .admin-actions a {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .admin-actions a:hover {
            background-color: #555;
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
            </ul>
        </nav>
    </header>

    <main>
        <h2>Votre Compte</h2>
        <div class="form-container">
            <form method="post" action="compte.php">
                <input type="hidden" name="update_info" value="1">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>

                <button type="submit">Mettre à jour</button>
            </form>
        </div>

        <?php if ($typeUtilisateur === 'client'): ?>
            <h2>Prendre un Rendez-vous</h2>
            <div class="form-container">
                <form method="post" action="compte.php">
                    <input type="hidden" name="take_rendezvous" value="1">
                    <label for="propriete_id">Propriété :</label>
                    <select id="propriete_id" name="propriete_id" required>
                        <?php while ($row = $proprieteResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['propriete_id']) ?>"><?= htmlspecialchars($row['adresse']) ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="agent_id">Agent immobilier :</label>
                    <select id="agent_id" name="agent_id" required>
                        <?php while ($row = $agentResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="date_heure">Date et heure :</label>
                    <input type="datetime-local" id="date_heure" name="date_heure" required>

                    <button type="submit">Prendre Rendez-vous</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($typeUtilisateur === 'client' || $typeUtilisateur === 'agent'): ?>
            <h2>Vos Rendez-vous</h2>
            <div class="rendezvous-list">
                <?php if ($rdvResult->num_rows > 0): ?>
                    <?php while ($rdv = $rdvResult->fetch_assoc()): ?>
                        <div class="rendezvous-item">
                            <h3>Rendez-vous avec <?= htmlspecialchars($typeUtilisateur == 'client' ? $rdv['agent_prenom'] . ' ' . $rdv['agent_nom'] : $rdv['client_prenom'] . ' ' . $rdv['client_nom']) ?></h3>
                            <p>Date et heure : <?= htmlspecialchars($rdv['date_heure']) ?></p>
                            <p>Adresse : <?= htmlspecialchars($rdv['adresse']) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Aucun rendez-vous trouvé.</p>
                <?php endif; ?>
            </div>
        <?php elseif ($typeUtilisateur === 'administrateur'): ?>
            <h2>Compte admin</h2>
        <?php endif; ?>

        <?php if ($typeUtilisateur === 'administrateur'): ?>
        <div class="admin-actions">
            <a href="ajouter_propriete.php">Ajouter une propriété</a>
            <a href="ajouter_agent.php">Ajouter un agent</a>
            <a href="gerer_disponibilite.php">Gérer la disponibilité des agents</a>
        </div>
        <?php endif; ?>

        <div class="logout-button">
            <form method="post" action="logout.php">
                <button type="submit">Déconnexion</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>
