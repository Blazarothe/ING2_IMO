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
    <section class="form signup">
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="name-details">
          <div class="field input">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="First name" required>
          </div>
          <div class="field input">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Last name" required>
          </div>
        </div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter new password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Valider">
        </div>
      </form>
      <div class="link">Already signed up? <a href="login.php">Login now</a></div>
    </section>
  </div>

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>

</body>
</html>
