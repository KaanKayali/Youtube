<?php
    session_start();
    require('config.php');
    if(isset($_POST['email'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT password FROM users WHERE email='$email'";
        $query = mysqli_query($conn, $query);
        $rows = mysqli_num_rows($query);

        if($rows == 1 && password_verify($password, mysqli_fetch_column($query))){
            $_SESSION['email'] = $email;
            echo $email;
        } else{
            echo "Incorrect Email or password.";
        }
    } 
?>