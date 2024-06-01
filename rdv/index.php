<?php
session_start();

include_once "config.php";

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
    <title>Accueil - Omnes Immobilier</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .properties {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
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
            display: block;
            margin-bottom: 10px;
        }
        .property h3 {
            color: #333;
            font-size: 1.5em;
        }
        .property p {
            color: #666;
        }
        .event-of-the-week {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
        .event-of-the-week h3 {
            margin-bottom: 10px;
            font-size: 1.8em;
            color: #333;
        }
        .event-of-the-week p {
            color: #666;
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
                <li><a href="logout.php">Déconnexion</a></li>
                <li><a href="../login/login.php">Chat</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Événement de la semaine</h2>
        <div class="event-of-the-week">
            <h3>Ouverture de notre nouvelle agence à Paris</h3>
            <p>Nous sommes heureux d'annoncer l'ouverture de notre nouvelle agence située au cœur de Paris. Rejoignez-nous pour célébrer cet événement avec des offres spéciales et des consultations gratuites.</p>
        </div>

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
