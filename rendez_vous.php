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

// Annuler un rendez-vous
if (isset($_POST['cancel_rdv_id'])) {
    $rdvId = $_POST['cancel_rdv_id'];
    $updateSql = "UPDATE RendezVous SET statut='annulé' WHERE id=?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $rdvId);
    $stmt->execute();
}

// Récupérer les rendez-vous confirmés pour l'utilisateur ou l'agent
$rdvSql = ($typeUtilisateur == 'client') ? 
    "SELECT rv.id AS rdv_id, rv.date_heure, p.adresse, u.nom AS agent_nom, u.prenom AS agent_prenom, a.telephone, p.digicode FROM RendezVous rv JOIN Proprietes p ON rv.propriete_id = p.propriete_id JOIN Utilisateurs u ON rv.agent_id = u.id JOIN Agents a ON rv.agent_id = a.id WHERE rv.client_id = ? AND rv.statut = 'confirmé'" :
    "SELECT rv.id AS rdv_id, rv.date_heure, p.adresse, u.nom AS client_nom, u.prenom AS client_prenom, u.email, p.digicode FROM RendezVous rv JOIN Proprietes p ON rv.propriete_id = p.propriete_id JOIN Utilisateurs u ON rv.client_id = u.id WHERE rv.agent_id = ? AND rv.statut = 'confirmé'";

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
    <title>Rendez-vous - Omnes Immobilier</title>
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
        .rendezvous {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .rendezvous-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rendezvous-item h3 {
            color: #333;
            font-size: 1.5em;
        }
        .rendezvous-item p {
            color: #666;
        }
        .rendezvous-item form {
            text-align: center;
        }
        .rendezvous-item button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
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
            </ul>
        </nav>
    </header>

    <main>
        <h2>Vos Rendez-vous Confirmés</h2>
        <div class="rendezvous">
            <?php if ($rdvResult->num_rows > 0): ?>
                <?php while ($rdv = $rdvResult->fetch_assoc()): ?>
                    <div class="rendezvous-item">
                        <h3>Rendez-vous avec <?= htmlspecialchars($typeUtilisateur == 'client' ? $rdv['agent_prenom'] . ' ' . $rdv['agent_nom'] : $rdv['client_prenom'] . ' ' . $rdv['client_nom']) ?></h3>
                        <p>Date et heure : <?= htmlspecialchars($rdv['date_heure']) ?></p>
                        <p>Adresse : <?= htmlspecialchars($rdv['adresse']) ?></p>
                        <p>Digicode : <?= htmlspecialchars($rdv['digicode'] ?? '') ?></p>
                        <p>Téléphone : <?= htmlspecialchars($typeUtilisateur == 'client' ? $rdv['telephone'] ?? '' : $rdv['email'] ?? '') ?></p>
                        <form method="post" action="rendez_vous.php">
                            <input type="hidden" name="cancel_rdv_id" value="<?= htmlspecialchars($rdv['rdv_id']) ?>">
                            <button type="submit">Annuler le RDV</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun rendez-vous confirmé trouvé.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>

