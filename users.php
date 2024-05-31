<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}
?>
<?php include_once "header.php"; ?>
<?php include_once "php/config.php"; ?>
<body>
    <div class="mail-wrapper">
        <section class="mail-sidebar">
            <header>
                <div class="content">
                    <?php 
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <div class="details">
                        <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
                        <p><?php echo $row['status']; ?></p>
                    </div>
                </div>
                <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
            </header>
            <div class="emails-list">
                <h3>Received Emails</h3>
                <?php 
                // Fetch received emails from the database
                $email_sql = mysqli_query($conn, "SELECT * FROM emails WHERE incoming_msg_id = {$_SESSION['unique_id']} ORDER BY email_id DESC");
                if (mysqli_num_rows($email_sql) > 0) {
                    while ($email_row = mysqli_fetch_assoc($email_sql)) {
                        // Fetch sender's information
                        $sender_sql = mysqli_query($conn, "SELECT fname, lname FROM users WHERE unique_id = {$email_row['outgoing_msg_id']}");
                        $sender_row = mysqli_fetch_assoc($sender_sql);
                        $sender_name = $sender_row['fname'] . ' ' . $sender_row['lname'];

                        echo '<div class="email-item">';
                        echo '<span class="email-sender">' . htmlspecialchars($sender_name, ENT_QUOTES, 'UTF-8') . '</span>';
                        echo '<span class="email-subject">' . htmlspecialchars($email_row['subject'], ENT_QUOTES, 'UTF-8') . '</span>';
                        echo '<span class="email-date">' . htmlspecialchars($email_row['date'], ENT_QUOTES, 'UTF-8') . '</span>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No emails found.</p>';
                }
                ?>
            </div>
        </section>
        <section class="mail-content">
            <header>
                <h3>Compose New Message</h3>
            </header>
            <form id="send-email-form" action="php/send_email.php" method="POST">
                <div class="field">
                    <label for="recipient">To:</label>
                    <select name="recipient" id="recipient" required>
                        <?php
                        // Fetch all users from the database
                        $users_sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id != {$_SESSION['unique_id']}");
                        if (mysqli_num_rows($users_sql) > 0) {
                            while ($user_row = mysqli_fetch_assoc($users_sql)) {
                                echo '<option value="' . htmlspecialchars($user_row['email'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($user_row['fname'] . ' ' . $user_row['lname'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="field">
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" id="subject" required>
                </div>
                <div class="field">
                    <label for="message">Message:</label>
                    <textarea name="message" id="message" required></textarea>
                </div>
                <div class="field">
                    <button type="submit">Send</button>
                </div>
            </form>
            <div class="sent-emails-list">
                <h3>Sent Emails</h3>
                <?php include_once "php/users.php"; ?>
            </div>
        </section>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .mail-wrapper {
            display: flex;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .mail-sidebar, .mail-content {
            padding: 20px;
        }
        .mail-sidebar {
            width: 30%;
            border-right: 1px solid #ddd;
        }
        .mail-content {
            width: 70%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .emails-list, .sent-emails-list {
            margin-top: 20px;
        }
        .email-item {
            margin-bottom: 10px;
        }
        .email-sender, .email-recipient {
            font-weight: bold;
        }
        .field {
            margin-bottom: 10px;
        }
        .field label {
            display: block;
            margin-bottom: 5px;
        }
        .field input, .field textarea, .field select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>

    <script>
        document.getElementById('send-email-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            var formData = new FormData(this);
            
            fetch('php/send_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Email sent successfully.');
                    // Clear the subject and message fields
                    document.getElementById('subject').value = '';
                    document.getElementById('message').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
