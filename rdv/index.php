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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        .properties-carousel .property img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            display: block;
            margin-bottom: 10px;
        }
        .slick-prev, .slick-next {
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
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
        <h1>Omnes Immobilier</h1>
        <div class="event-of-the-week">
            <p>Bienvenue chez Omnes Immobilier, votre agence immobilière de confiance à Paris. Nous offrons des services de qualité pour l'achat, la vente, la location et la gestion locative de biens immobiliers. Notre équipe expérimentée s'engage à fournir des conseils personnalisés et transparents à chaque étape de votre projet. Que vous recherchiez la maison de vos rêves, souhaitiez vendre votre propriété au meilleur prix, ou nécessitiez une gestion locative fiable, Omnes Immobilier est là pour vous accompagner.</p>
        </div>
        <h2>Événement de la semaine</h2>
        <div class="event-of-the-week">
            <h3>Ouverture de notre nouvelle agence à Paris</h3>
            <p>Nous sommes heureux d'annoncer l'ouverture de notre nouvelle agence située au cœur de Paris. Rejoignez-nous pour célébrer cet événement avec des offres spéciales et des consultations gratuites.</p>
        </div>

        <h2>Liste des Biens Immobiliers</h2>
        <div class="properties-carousel">
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="property">
                        <img src="<?= htmlspecialchars($property['photo_url']) ?>" alt="Photo de la propriété">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.properties-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
</body>
</html>