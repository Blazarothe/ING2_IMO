<?php
    session_start();
    include_once "ChatApp/php/config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px; 
        }
        .separator {
            height: 1px;
            margin: 20px 0;
            background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
        }
        .button {
            display: block;
            width: calc(100% - 20px); 
            margin: 20px auto; /* Augmentation de la marge */
            padding: 10px 20px; 
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background: #333;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px #999;
            transition: all 0.3s ease;
        }
        .button:hover {
            background-color: black;
        }
        .button:active {
            background-color: #333;
            box-shadow: 0 3px #666;
            transform: translateY(2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contactez-nous</h1>
        <div class="separator"></div>
        <a href="mailto:ronanmeyergo@gmail.com" class="button">Email</a>
        <a href="chatapp/login.php" class="button">Chat Online</a>
        <a href="https://teams.microsoft.com/l/meetup-join/19%3ameeting_OTJhZGQ5NzEtOTQ5MC00MmU5LWE1ZjYtNjBmNGViZjkwMzUx%40thread.v2/0?context=%7b%22Tid%22%3a%22a2697119-66c5-4126-9991-b0a8d15d367f%22%2c%22Oid%22%3a%22f789ff9d-88c1-4262-a387-926f1e4349c8%22%7d" class="button">Appel Audio</a>
        <a href="https://teams.microsoft.com/l/meetup-join/19%3ameeting_OTJhZGQ5NzEtOTQ5MC00MmU5LWE1ZjYtNjBmNGViZjkwMzUx%40thread.v2/0?context=%7b%22Tid%22%3a%22a2697119-66c5-4126-9991-b0a8d15d367f%22%2c%22Oid%22%3a%22f789ff9d-88c1-4262-a387-926f1e4349c8%22%7d" class="button">Appel Vidéo</a>
    </div>
</body>
</html>

