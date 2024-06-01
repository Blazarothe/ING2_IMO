<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){
                echo "$email - This email already exist!";
            }else{
                $ran_id = rand(time(), 100000000);
                $status = "Active now";
                $encrypt_pass = md5($password);
                $role = '1';
                $insert_query = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, status, role) VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$status}', '{$role}')");
                if($insert_query){
                    $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                    if(mysqli_num_rows($select_sql2) > 0){
                        $result = mysqli_fetch_assoc($select_sql2);
                        $_SESSION['unique_id'] = $result['unique_id'];
                        echo "success";
    }else{
        echo "This email address not Exist!";
    }
}else{
    echo "Something went wrong. Please try again!";
}

            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }

$servername2 = "localhost";
$username2 = "root"; // Replace with your database username
$password2 = ""; // Replace with your database password
$dbname2 = "OmnesImmobilier";

// Create connection
$conn2 = new mysqli($servername2, $username2, $password2, $dbname2);

// Check connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}

// Retrieve form data
$nom = $_POST['lname'];
$prenom = $_POST['fname'];
$email = $_POST['email'];
$mot_de_passe = $_POST['password']; // Hash the password for security
$type_utilisateur = 'client'; // Fixed user type

// Prepare and bind
$stmt = $conn2->prepare("INSERT INTO Utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe, $type_utilisateur);
$stmt->execute()

?>