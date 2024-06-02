<?php
include 'config.php'; // Database configuration

// Fetch agents with their first name, last name, and email from the utilisateurs table
$sql = "SELECT agents.id, utilisateurs.prenom, utilisateurs.nom, utilisateurs.email, agents.telephone, agents.photo_profil_url, agents.cv, agents.disponibilite
        FROM agents
        JOIN utilisateurs ON agents.utilisateur_id = utilisateurs.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agents List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logoomnes.webp" alt="Logo">
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
        <h2>Agents List</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Photo</th>
                    <th>CV</th>
                    <th>Disponibilite</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["prenom"] . "</td>";
                        echo "<td>" . $row["nom"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["telephone"] . "</td>";
                        echo "<td><img src='" . $row["photo_profil_url"] . "' alt='Photo'></td>";
                        echo "<td>" . $row["cv"] . "</td>";
                        echo "<td>" . $row["disponibilite"] . "</td>";
                        echo '<td><form action="remove_agent.php" method="POST" style="display:inline;">
                                <input type="hidden" name="agent_email" value="' . $row["email"] . '">
                                <button type="submit">Remove</button>
                              </form></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No agents found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2023 Omnes Immobilier</p>
    </footer>
</body>
</html>
