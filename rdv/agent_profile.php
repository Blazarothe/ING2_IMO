<?php
session_start();

include_once "config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Vérifier si l'ID de l'agent est passé en requête GET
if (!isset($_GET['agent_id'])) {
    echo "Agent non spécifié.";
    exit();
}

$agentId = $_GET['agent_id'];

$agent = null;

// Récupérer les informations de l'agent et ses disponibilités
$sql = "SELECT a.id, u.nom, u.prenom, a.disponibilite, a.photo_profil_url 
        FROM Agents a 
        JOIN Utilisateurs u ON a.utilisateur_id = u.id
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $agent = $result->fetch_assoc();

    // Récupérer les rendez-vous de l'agent
    $rdvSql = "SELECT date_heure FROM RendezVous WHERE agent_id = ? AND statut = 'confirmé'";
    $stmt = $conn->prepare($rdvSql);
    $stmt->bind_param("i", $agentId);
    $stmt->execute();
    $rdvResult = $stmt->get_result();

    $rendezvous = [];
    if ($rdvResult->num_rows > 0) {
        while ($rdvRow = $rdvResult->fetch_assoc()) {
            $rendezvous[] = $rdvRow['date_heure'];
        }
    }
    $agent['rendezvous'] = $rendezvous;
} else {
    echo "Agent non trouvé.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Agent - Omnes Immobilier</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .agent {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px auto;
            text-align: center;
        }
        .agent h3 {
            color: #333;
            font-size: 1.5em;
        }
        .agent p {
            color: #666;
        }
        .agent .buttons {
            margin-top: 10px;
        }
        .agent .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .agent .buttons form {
            display: inline-block;
        }
        .agent img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            margin-bottom: 10px;
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
                <li><a href="chat.php">Chat</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Profil de l'Agent</h2>
        <?php if ($agent): ?>
            <div class="agent">
                <img src="<?= htmlspecialchars($agent['photo_profil_url']) ?>" alt="Photo de profil de l'agent">
                <h3><?= htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']) ?></h3>
                <p>Disponibilités : <?= nl2br(htmlspecialchars($agent['disponibilite'])) ?></p>
                <p>Non disponible à ces dates:</p>
                <ul>
                    <?php if (count($agent['rendezvous']) > 0): ?>
                        <?php foreach ($agent['rendezvous'] as $rdv): ?>
                            <li><?= htmlspecialchars($rdv) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Aucun rendez-vous programmé.</li>
                    <?php endif; ?>
                </ul>
                <div class="buttons">
                    <form action="compte.php" method="GET">
                        <button type="submit" name="agent_id" value="<?= htmlspecialchars($agent['id']) ?>">Prendre Rendez-vous</button>
                    </form>
                    <form action="chat.php" method="GET">
                        <button type="submit" name="agent_id" value="<?= htmlspecialchars($agent['id']) ?>">Communiquer</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Aucun agent trouvé.</p>
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
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>


