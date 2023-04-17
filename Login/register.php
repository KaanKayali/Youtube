<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="../script.js" defer></script>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div id="page">
        <h1 style="color:white">Register</h1>
        <form id="mainRegister" action="commitRegister.php" method="POST" enctype="multipart/form-data">
            <div id="register">
                <div class="pb-upload">
                    <label for="pb">
                        <img src="profilePictures/profilePicture.png" width='50px' height='50px' id="imgpb"/>
                    </label>
                    <input type="file" name="pb" id="pb" onchange="replaceImage()"/>
                    
                </div><br><br>

                <label for="name">Username:</label>
                <input type="text" name="name" id="name" class="textfield" required><br><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="textfield" required pattern="^[\w\.-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-zA-Z]{2,}$"><br><br>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="textfield" required minlength="6" maxlength="20" onkeydown='document.getElementById("regist").removeChild(div);'><br><br>
            </div>
            <input type="submit" name="submit" id="submit" class="button" value="Create Account">
            <p>Already have an account? <a href="login.php" style="color: white">Login</a></p>
        </form><br>
        <a href="../index.php">
            <button class="button">Back</button>
        </a>
    </div>
    <div id="messageDiv">
        <h1 id="loginMessage"></h1><br>
        <h1 style="display:none" id="redirectLbl">Redirecting...</h1>
    </div>
    <script defer>
        function replaceImage() {
            const file = document.getElementById("pb").files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                document.getElementById("imgpb").src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }


        document.getElementById("mainRegister").addEventListener("submit", e=> {
            const LOGINTOKEN = "logintoken";

            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
        
            fetch("commitRegister.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if(data !== "Email already exists in our database"){
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