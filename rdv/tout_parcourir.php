<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Alex2201"; // Remplacez par votre mot de passe
$dbname = "OmnesImmobilier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer tous les biens immobiliers
$sql = "SELECT * FROM Proprietes";
$result = $conn->query($sql);

// Stocker les résultats dans un tableau
$properties = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Parcourir - Omnes Immobilier</title>
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
        .properties {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .property {
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
        .property h3 {
            color: #333;
            font-size: 1.5em;
        }
        .property p {
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
                <li><a href="chat.php">Chat</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Liste des Biens Immobiliers</h2>
        <div class="properties">
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
                <p>Aucun bien immobilier trouvé.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Omnes Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>
