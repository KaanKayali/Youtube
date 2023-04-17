<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            document.getElementById("form").addEventListener("submit", function(e){
                if(document.getElementById("newPass").value != document.getElementById("verifyPass").value){
                    e.preventDefault();
                    let p = document.createElement("p");
                    p.innerText = "Passwords don't match";
                    p.style.color = "red";
                    document.getElementById("div").appendChild(p);
                } else{
                    return true;
                }
            });
        });
    </script>
    <style>
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        session_start();
        require('config.php');
        require('passcode.php');
        if(isset($_POST['email'])){
            if(!isset($_POST['code'])){
                $email = $_POST['email'];
                $checkEmail = "SELECT * FROM users WHERE email='$email'";
                $checkEmail = mysqli_query($conn, $checkEmail);
                $rows = mysqli_num_rows($checkEmail);

                if($rows == 1){
                    $code = getCode();
                    $_SESSION['ver'] = $code;
                    $_SESSION['email'] = $email;
                    $upper = strtoupper($code);
                    $to = $email;
                    $subject = "Password Reset.";
                    $msg = "
                        <html> 
                        <head> 
                            <title>Password Reset</title> 
                        </head> 
                        <body> 
                            <h2 style='font-family: Trebuchet MS, sans-serif''>Your password reset code:</h2><br>
                            <h1 style='background-color: rgb(4, 3, 3);width:200px;color:rgb(15, 251, 15);height: 60px; padding-left:35px;padding-top:20px;letter-spacing: 12px;font-family: Trebuchet MS, sans-serif'>$upper</h1>                        
                        </body> 
                        </html>
                    ";
                    $headers = "Content-type:text/html;charset=UTF-8" . "\r\n"; 
                    $headers .= "From: malisilejs@gmail.com" . "\r\n";
                    mail($to, $subject, $msg, $headers);

                    echo "
                        <div>
                            <form action='' method='POST' id='mainLogin'>
                            <fieldset>
                                <legend>Please enter your code</legend>
                                <p>We sent a code to $email</p>
                                <label for='code'>Code</label>
                                <input name='code' id='code' type='text' required class='textfield'>
                                <input name='email' id='email' type='email' value='$email' hidden>
                            </fieldset><br>
                            <input type='submit' name='enter' id='submit' class='button'>
                            </form><br>
                            <a href='login.php' style='color:white'>Back</a>
                        </div>
                    ";
                } else{
                    echo "
                        <div>
                            <h1>This Email does not exist in our database.</h1>
                            <p>Please <a href='forgot.php' style='color:white'>Try again</a></p>
                        </div>
                    ";
                } 
            } else{
                if (strtolower($_POST['code']) == $_SESSION['ver']){
                    $verify = $_SESSION['ver'];
                    $email = $_SESSION['email'];
                    if(!isset($_POST['newPass'])){
                        echo "
                            <div id='div'>
                                <form action='' method='POST' id='mainLogin'>
                                <fieldset>
                                    <legend>Please enter your code</legend>
                                    <label for='newPass'>New Password</label>
                                    <input name='newPass' id='newPass' class='textfield' type='password' placeholder='Password...' required><br><br>
                                    <label for='verifyPass'>Verify Password</label>
                                    <input name='verifyPass' id='verifyPass' class='textfield' type='password' placeholder='Repeat...' required>
                                    <input name='code' id='code' type='text' value='$verify' hidden>
                                    <input name='email' id='email' type='email' value='$email' hidden>
                                </fieldset><br>
                                <input type='submit' name='enter' id='submit' class='button'>
                                </form><br>
                                <a href='login.php'>Back</a>
                            </div>
                        ";
                    } else{
                        $password = mysqli_real_escape_string($conn, $_POST['newPass']);
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $email = $_SESSION['email'];

                        $query = "UPDATE users SET password='$hash' WHERE email='$email'";
                        $query = mysqli_query($conn, $query);

                        echo "
                            <div id='massage'>
                                <h1>Password changed successfully.</h1>
                                <p>Click here to <a href='login.php'>Login</a></p>
                            </div>
                        ";
                    }
                } else{
                    echo "
                    <div id='massage'>
                        <h1>Code is invalid or expired.</h1>
                        <p>Click here to <a href='forgot.php'>Try again</a></p>
                    </div>
                    ";
                }
            }

            
        } 
        else{
    ?>
    <div>
        <form action="" method="POST" id='mainLogin'>
            <fieldset>
                <legend>Please enter your email</legend>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']?>" required class="textfield">
            </fieldset><br>
            <input type="submit" name="submit" id="submit" class="button">
        </form><br>
        <a href="login.php" style="color:white">Back</a>
    </div>
    <?php
        }
    ?>
</body>
</html>