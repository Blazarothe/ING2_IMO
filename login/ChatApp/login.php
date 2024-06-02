<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: users.php");
  }
?>

<?php include_once "header.php"; ?>

<body>
    <header>
        <h1>Omnes Immobilier</h1>
        <nav>
            <ul>
                <li><a href="../../rdv/index.php">Accueil</a></li>
                <li><a href="../../rdv/tout_parcourir.php">Tout Parcourir</a></li>
                <li><a href="../../rdv/rechercher.php">Recherche</a></li>
                <li><a href="../../rdv/rendez_vous.php">Rendez-vous</a></li>
                <li><a href="../../rdv/compte.php">Votre Compte</a></li>
                <li><a href="../../rdv/chat.php">Chat</a></li>
            </ul>
        </nav>
    </header>
    <div class="wrapper">
        <section class="form login">
            <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="field input">
                    <label>Email Address</label>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>
                <div class="field input">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye"></i>
                </div>
                <div class="field button">
                    <input type="submit" name="submit" value="Continue to Chat">
                </div>
            </form>
            <div class="link">Not yet signed up? <a href="index.php">Signup now</a></div>
        </section>
    </div>
    
    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/login.js"></script>
</body>
</html>
