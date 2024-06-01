<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];

    // Obtenir le rôle de l'utilisateur actuel
    $role_query = mysqli_query($conn, "SELECT role FROM users WHERE unique_id = {$outgoing_id}");
    $role_row = mysqli_fetch_assoc($role_query);
    $current_user_role = $role_row['role'];

    if ($current_user_role == 1) {
        $sql = "SELECT * FROM users WHERE unique_id != {$outgoing_id} AND role = 2 ORDER BY user_id DESC";
    } else if ($current_user_role == 2) {
        $sql = "SELECT DISTINCT users.* 
                FROM users 
                JOIN messages ON users.unique_id = messages.outgoing_msg_id 
                WHERE messages.incoming_msg_id = {$outgoing_id} AND users.role = 1
                ORDER BY users.user_id DESC";   
    }

    $query = mysqli_query($conn, $sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>
