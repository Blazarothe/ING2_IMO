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

// Initialisation des variables
$searchQuery = "";
$properties = [];
$agents = [];

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
        }
        .property p, .agent p {
            color: #666;
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
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun agent trouvé.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>

