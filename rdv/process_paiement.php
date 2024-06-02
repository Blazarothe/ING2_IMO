<?php
session_start();
include_once "config.php";

// Vérifier si l'utilisateur est connecté et est un client
if (!isset($_SESSION['user_id']) || $_SESSION['type_utilisateur'] !== 'client') {
    header("Location: login.php");
    exit();
}

// Récupérer les données du formulaire
$proprieteId = isset($_POST['property_id']) ? $_POST['property_id'] : '';
$prix = isset($_POST['prix']) ? $_POST['prix'] : '';
$cardNumber = isset($_POST['card_number']) ? $_POST['card_number'] : '';
$cardExpiry = isset($_POST['card_expiry']) ? $_POST['card_expiry'] : '';
$cardCvc = isset($_POST['card_cvc']) ? $_POST['card_cvc'] : '';

// Vérifier que toutes les données nécessaires sont présentes
if (!$proprieteId || !$prix || !$cardNumber || !$cardExpiry || !$cardCvc) {
    echo "Informations de paiement manquantes.";
    exit();
}

// Supprimer la propriété achetée de la base de données
$sql = "DELETE FROM Proprietes WHERE propriete_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $proprieteId);
$stmt->execute();

// Rediriger vers la page de confirmation
header("Location: confirmation_paiement.php");
exit();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Omnes Immobilier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .container p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container form input {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
        }
        .container form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container form button:hover {
            background-color: #45a049;
        }
        .message {
            font-size: 18px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Paiement pour la propriété ID: <?= htmlspecialchars($proprieteId) ?></h2>
        <p>Montant à payer: <?= number_format($prix, 2) ?> €</p>
        <form method="post" action="process_paiement.php">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($proprieteId) ?>">
            <input type="hidden" name="prix" value="<?= htmlspecialchars($prix) ?>">
            <input type="text" name="card_number" placeholder="Numéro de carte bancaire" required>
            <input type="text" name="card_expiry" placeholder="Date d'expiration (MM/AA)" required>
            <input type="text" name="card_cvc" placeholder="CVC" required>
            <button type="submit">Payer</button>
        </form>
    </div>
</body>
</html>
