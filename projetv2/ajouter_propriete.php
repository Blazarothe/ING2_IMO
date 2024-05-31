<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'administrateur') {
    header("Location: login.php");
    exit();
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

// Clear session messages
unset($_SESSION['message']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Propriété - Omnes Immobilier</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input, .form-container select, .form-container textarea {
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
        .message, .error {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
                <li><a href="accueil_admin.php">Accueil Admin</a></li>
                <li><a href="ajouter_propriete.php">Ajouter une Propriété</a></li>
                <li><a href="ajouter_agent.php">Ajouter un Agent</a></li>
                <li><a href="gerer_disponibilite.php">Gérer la Disponibilité des Agents</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Ajouter une Propriété</h2>
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="traiter_ajouter_propriete.php" method="post">
                <label for="propriete_id">ID de la Propriété :</label>
                <input type="text" id="propriete_id" name="propriete_id" required>
                
                <label for="type_propriete">Type de Propriété :</label>
                <select id="type_propriete" name="type_propriete" required>
                    <option value="résidentiel">Résidentiel</option>
                    <option value="commercial">Commercial</option>
                    <option value="terrain">Terrain</option>
                    <option value="appartement">Appartement</option>
                </select>
                
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" required>
                
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" required>
                
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                
                <label for="prix">Prix (€) :</label>
                <input type="number" id="prix" name="prix" step="0.01" required>
                
                <label for="dimension">Dimensions :</label>
                <input type="text" id="dimension" name="dimension" required>
                
                <label for="photo_url">URL de la Photo :</label>
                <input type="text" id="photo_url" name="photo_url" required>
                
                <label for="agent_id">ID de l'Agent :</label>
                <input type="text" id="agent_id" name="agent_id" required>
                
                <label for="digicode">Digicode :</label>
                <input type="text" id="digicode" name="digicode" required>
                
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="contact-info">
            <p>Email: contact@omnesimmobilier.fr</p>
            <p>Téléphone: +33 01 23 45 67 89</p>
            <p>Adresse: 10 Rue Sextius Michel, Paris, France</p>
        </div>
    </footer>
</body>
</html>

