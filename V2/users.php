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
            <div class="emails-list" id="received-emails">
                <h3>Received Emails</h3>
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
            <div class="sent-emails-list" id="sent-emails">
                <h3>Sent Emails</h3>
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
        function loadEmails() {
            fetch('php/get_received_emails.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('received-emails').innerHTML = '<h3>Received Emails</h3>' + data;
                });

            fetch('php/get_sent_emails.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('sent-emails').innerHTML = '<h3>Sent Emails</h3>' + data;
                });
        }

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
                    loadEmails();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Load emails initially and set an interval to refresh them
        loadEmails();
        setInterval(loadEmails, 10); // Refresh emails every 30 seconds
    </script>
</body>
</html>
