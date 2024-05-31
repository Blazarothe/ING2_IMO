<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="mail-area">
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }else{
            header("location: users.php");
          }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <div class="details">
          <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
          <p><?php echo $row['status']; ?></p>
        </div>
      </header>
      <div class="mail-box">
        <!-- Les emails seront affichés ici -->
      </div>
      <form action="#" class="compose-area">
        <input type="text" class="recipient" name="recipient" placeholder="Recipient" required>
        <input type="text" class="subject" name="subject" placeholder="Subject" required>
        <textarea name="message" class="input-field" placeholder="Type your message here..." rows="10" required></textarea>
        <button><i class="fab fa-telegram-plane"></i> Send</button>
      </form>
    </section>
  </div>

  <script src="javascript/mail.js"></script>
</body>
</html>
