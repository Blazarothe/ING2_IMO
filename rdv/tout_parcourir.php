<?php
include_once "config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialiser le type de propriété
$typePropriete = isset($_GET['type_propriete']) ? $_GET['type_propriete'] : '';

// Construire la requête SQL avec le filtre de type de propriété
$sql = "SELECT * FROM Proprietes";
if ($typePropriete) {
    $sql .= " WHERE type_propriete = ?";
}

$stmt = $conn->prepare($sql);
if ($typePropriete) {
    $stmt->bind_param("s", $typePropriete);
}
$stmt->execute();
$result = $stmt->get_result();

// Stocker les résultats dans un tableau
$properties = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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
        .filter-form {
            text-align: center;
            margin-bottom: 20px;
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .property img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .property h3 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        .property p {
            color: #666;
            margin: 5px 0;
            text-align: center;
        }
        .property .btn {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            width: 100%;
        }
        .property .btn:hover {
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
        <h2>Liste des Biens Immobiliers</h2>
        <div class="filter-form">
            <form method="get" action="tout_parcourir.php">
                <label for="type_propriete">Filtrer par type de bien :</label>
                <select id="type_propriete" name="type_propriete">
                    <option value="">Tous</option>
                    <option value="résidentiel" <?= $typePropriete == 'résidentiel' ? 'selected' : '' ?>>Résidentiel</option>
                    <option value="commercial" <?= $typePropriete == 'commercial' ? 'selected' : '' ?>>Commercial</option>
                    <option value="terrain" <?= $typePropriete == 'terrain' ? 'selected' : '' ?>>Terrain</option>
                    <option value="appartement" <?= $typePropriete == 'appartement' ? 'selected' : '' ?>>Appartement</option>
                </select>
                <button type="submit">Filtrer</button>
            </form>
        </div>
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
                        <a href="agent_profile.php?agent_id=<?= htmlspecialchars($property['agent_id']) ?>" class="btn">Voir le profil de l'agent</a>
                        <a href="paiement.php?propriete_id=<?= htmlspecialchars($property['propriete_id']) ?>&prix=<?= htmlspecialchars($property['prix']) ?>" class="btn">Acheter</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun bien immobilier trouvé.</p>
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


