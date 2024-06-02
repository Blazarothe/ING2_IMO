<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
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
    <section class="users">
      <header>
        <div class="content">
          <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>

      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>
</html>