<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div id="page">
        <h1 style="color:white">Login</h1>
        <form id="mainLogin" action="commitLogin.php" method="POST" enctype="multipart/form-data">
            <div id="login">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']?>" class="textfield" required><br>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="textfield" required><br>
                
                <input type="submit" name="submit" id="submit" class="button">
                <p><a href="forgot.php" style="color:red">Forgot password?</a><br>Don't have an account yet? <a href="register.php" style="color:white">Register</a></p>
            </div>
        </form>
        <a href="../index.php">
            <button class="button">Back</button>
        </a>
    </div>
    <div id="messageDiv">
        <h1 id="loginMessage"></h1><br>
        <h1 style="display:none" id="redirectLbl">Redirecting...</h1>
    </div>
    <script defer>
        document.getElementById("mainLogin").addEventListener("submit", e=> {
            const LOGINTOKEN = "logintoken";

            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
        
            fetch("commitLogin.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if(data !== "Incorrect Email or password."){
                    localStorage.setItem(LOGINTOKEN, data);
                    document.getElementById("page").innerHTML = "";
                    document.getElementById("loginMessage").innerText = "Logged In.";
                    document.getElementById("redirectLbl").style.display = "";
                    setTimeout(function(){
                        window.location.replace("../index.php");
                    }, 2000)
                } else{
                    document.getElementById("page").innerHTML = "";
                    document.getElementById("loginMessage").innerText = data;
                    document.getElementById("redirectLbl").innerText = "Reloading...";
                    document.getElementById("redirectLbl").style.display = "";
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000)
                }
            })
            .catch(error => {
                console.error(error);
            });
        })
    </script>
</body>
</html>