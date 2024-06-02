<?php
session_start();

include_once "config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialisation des variables
$searchQuery = "";
$properties = [];
$agents = [];
$agentProperties = []; // Tableau pour stocker les propriétés par agent

// Vérifier si une recherche a été effectuée
if (isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];

    // Construire la requête SQL pour rechercher par ID ou ville dans les propriétés
    $sql = "SELECT * FROM Proprietes WHERE propriete_id LIKE ? OR ville LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Stocker les résultats dans un tableau
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    }

    // Construire la requête SQL pour rechercher par nom d'agent
    $sql = "SELECT a.id, u.nom, u.prenom, u.email, a.telephone 
            FROM Agents a 
            JOIN Utilisateurs u ON a.utilisateur_id = u.id 
            WHERE u.nom LIKE ? OR u.prenom LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Stocker les résultats dans un tableau
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $agents[] = $row;

            // Récupérer les propriétés associées à cet agent
            $agentId = $row['id'];
            $sqlProperties = "SELECT * FROM Proprietes WHERE agent_id = ?";
            $stmtProperties = $conn->prepare($sqlProperties);
            $stmtProperties->bind_param("i", $agentId);
            $stmtProperties->execute();
            $resultProperties = $stmtProperties->get_result();

            if ($resultProperties->num_rows > 0) {
                while ($property = $resultProperties->fetch_assoc()) {
                    $agentProperties[$agentId][] = $property;
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Omnes Immobilier</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo img {
            width: 150px;
            margin-left: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin-right: 20px;
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
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form input {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
        }
        .search-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .results {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .property, .agent {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .property img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .property h3, .agent h3 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        .property p, .agent p {
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        .property .btn, .agent .btn {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            display: block;
        }
        .property .btn:hover, .agent .btn:hover {
            background-color: #555;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
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
                <li><a href="chat.php">Chat</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Recherche de Biens Immobiliers et d'Agents</h2>
        <div class="search-form">
            <form method="GET" action="rechercher.php">
                <input type="text" name="search_query" placeholder="Entrez un ID, une ville ou un nom d'agent" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        <div class="results">
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="property">
                        <img src="<?= htmlspecialchars($property['photo_url']) ?>" alt="Photo de la propriété">
                        <h3><?= ucfirst(htmlspecialchars($property['type_propriete'])) ?> à <?= htmlspecialchars($property['ville']) ?></h3>
                        <p>ID: <?= htmlspecialchars($property['propriete_id']) ?></p>
                        <p>Adresse: <?= htmlspecialchars($property['adresse']) ?></p>
                        <p>Description: <?= htmlspecialchars($property['description']) ?></p>
                        <p>Dimensions: <?= htmlspecialchars($property['dimension']) ?></p>
                        <p>Prix: <?= number_format($property['prix'], 2) ?> €</p>
                        <p>Agent ID: <?= htmlspecialchars($property['agent_id']) ?></p>
                        <a href="agent_profile.php?agent_id=<?= htmlspecialchars($property['agent_id']) ?>" class="btn">Voir le profil de l'agent</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune propriété trouvée.</p>
            <?php endif; ?>

            <?php if (count($agents) > 0): ?>
                <?php foreach ($agents as $agent): ?>
                    <div class="agent">
                        <h3>Agent: <?= htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']) ?></h3>
                        <p>Email: <?= htmlspecialchars($agent['email']) ?></p>
                        <p>Téléphone: <?= htmlspecialchars($agent['telephone']) ?></p>
                        <a href="agent_profile.php?agent_id=<?= htmlspecialchars($agent['id']) ?>" class="btn">Voir le profil de l'agent</a>
                        <?php if (isset($agentProperties[$agent['id']])): ?>
                            <div class="agent-properties">
                                <h4>Propriétés de l'agent :</h4>
                                <?php foreach ($agentProperties[$agent['id']] as $property): ?>
                                    <div class="property">
                                        <img src="<?= htmlspecialchars($property['photo_url']) ?>" alt="Photo de la propriété">
                                        <h3><?= ucfirst(htmlspecialchars($property['type_propriete'])) ?> à <?= htmlspecialchars($property['ville']) ?></h3>
                                        <p>ID: <?= htmlspecialchars($property['propriete_id']) ?></p>
                                        <p>Adresse: <?= htmlspecialchars($property['adresse']) ?></p>
                                        <p>Description: <?= htmlspecialchars($property['description']) ?></p>
                                        <p>Dimensions: <?= htmlspecialchars($property['dimension']) ?></p>
                                        <p>Prix: <?= number_format($property['prix'], 2) ?> €</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun agent trouvé.</p>
            <?php endif; ?>
        </div>
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



