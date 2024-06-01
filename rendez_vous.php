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

$rdvResult = null;

// Récupérer les rendez-vous confirmés pour l'utilisateur ou l'agent si l'utilisateur n'est pas un administrateur
if ($typeUtilisateur == 'client') {
    $rdvSql = "SELECT rv.id as rdv_id, rv.date_heure, p.adresse, p.digicode, ua.nom AS agent_nom, ua.prenom AS agent_prenom, a.telephone AS agent_telephone, a.photo_profil_url AS agent_photo, uc.nom AS client_nom, uc.prenom AS client_prenom, uc.email AS client_email 
               FROM RendezVous rv 
               JOIN Proprietes p ON rv.propriete_id = p.propriete_id 
               JOIN Agents a ON rv.agent_id = a.id
               JOIN Utilisateurs ua ON a.utilisateur_id = ua.id
               JOIN Utilisateurs uc ON rv.client_id = uc.id
               WHERE rv.client_id = ? AND rv.statut = 'confirmé'";
    $stmt = $conn->prepare($rdvSql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $rdvResult = $stmt->get_result();
} else if ($typeUtilisateur == 'agent') {
    $rdvSql = "SELECT rv.id as rdv_id, rv.date_heure, p.adresse, p.digicode, ua.nom AS agent_nom, ua.prenom AS agent_prenom, a.telephone AS agent_telephone, a.photo_profil_url AS agent_photo, uc.nom AS client_nom, uc.prenom AS client_prenom, uc.email AS client_email 
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
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous - Omnes Immobilier</title>
    <link rel="stylesheet" href="styles.css">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo img {
            max-height: 50px;
            margin-left: 20px;
        }
        nav {
            flex-grow: 1;
            text-align: right;
            margin-right: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
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
        .rendezvous-item img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            margin-bottom: 10px;
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
        }
        .contact-info {
            margin-bottom: 10px;
        }
        .map {
            margin: 0 auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logoomnes.webp" alt="Omnes Immobilier Logo">
        </div>
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
        <?php if ($typeUtilisateur == 'administrateur'): ?>
            <p>Compte administrateur - vous ne pouvez pas avoir de rendez-vous.</p>
        <?php else: ?>
            <div class="rendezvous">
                <?php if ($rdvResult && $rdvResult->num_rows > 0): ?>
                    <?php while ($rdv = $rdvResult->fetch_assoc()): ?>
                        <div class="rendezvous-item">
                            <img src="<?= htmlspecialchars($rdv['agent_photo']) ?>" alt="Photo de profil de l'agent">
                            <h3>Rendez-vous avec <?= htmlspecialchars($typeUtilisateur == 'client' ? $rdv['agent_prenom'] . ' ' . $rdv['agent_nom'] : $rdv['client_prenom'] . ' ' . $rdv['client_nom']) ?></h3>
                            <p>Date et heure : <?= htmlspecialchars($rdv['date_heure']) ?></p>
                            <p>Adresse : <?= htmlspecialchars($rdv['adresse']) ?></p>
                            <p>Digicode : <?= htmlspecialchars($rdv['digicode']) ?></p>
                            <p><?= $typeUtilisateur == 'client' ? 'Téléphone : ' . htmlspecialchars($rdv['agent_telephone']) : 'Mail : ' . htmlspecialchars($rdv['client_email']) ?></p>
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
        <?php endif; ?>
    </main>

    <footer>
        <div class="contact-info">
            <p>Email: contact@omnesimmobilier.fr</p>
            <p>Téléphone: +33 01 23 45 67 89</p>
            <p>Adresse: 10 Rue Sextius Michel, Paris, France</p>
        </div>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.6826763800837!2d2.2854042156743957!3d48.849381479287074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671c1a50ba2fb%3A0x61c73ae7a32aaec5!2s10%20Rue%20Sextius%20Michel%2C%2075005%20Paris%2C%20France!5e0!3m2!1en!2us!4v1622209168380!5m2!1en!2us" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </footer>
</body>
</html>


