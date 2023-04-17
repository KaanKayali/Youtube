<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <?php
        session_start();
        require('config.php');

        function prepareFile($name, $dir, $allowed){
            require "config.php";
        
            $filepath = $_FILES[$name]["tmp_name"];
            $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
            $filetype = finfo_file($fileinfo, $filepath);
            finfo_close($fileinfo);
        
            if(!in_array($filetype, array_keys($allowed))){
                die("File not Allowed");
            }
        
            $filename = $_FILES[$name]["name"];
            $targetDir = __DIR__ . "/" . $dir;
        
            $newPath = $targetDir . "/" . $filename;
        
            return [$filepath, $newPath, $filename];
        }

        if(isset($_POST['email'])){
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            $allowedImages = [
                "image/png" => "png",
                "image/jpeg" => "jpeg",
                "image/jpg" => "jpg"
            ];

            $filename = "";
            $pb = prepareFile("pb", "profilePictures", $allowedImages);

            if(!isset($_FILES['pb'])){
                $filename = "../profilePicture.png";
            } else{
                $filename = $pb[2];
            }



            $checkDuplicate = "SELECT * FROM users WHERE email='$email'";
            $checkDuplicate = mysqli_query($conn, $checkDuplicate);
            $rows = mysqli_num_rows($checkDuplicate);
                

            if($rows == 0 && move_uploaded_file($pb[0], $pb[1])){
                $query = "INSERT INTO users (pb, username, email, password)
                      VALUES ('$filename', '$name', '$email', '$hash')";

                $query = mysqli_query($conn, $query);

                if($query){
                    $_SESSION['email'] = $email;
                    echo "
                        <div>
                            <h1>Successfully Registered.</h1>
                            <p>Click here to <a href='login.php'>Login</a></p>
                        </div>
                    ";
                }
            } else{
                echo "
                        <div>
                            <h1>This Email already exists in our database.</h1>
                            <p>Please use a different email <a href='register.php'>Register</a></p>
                        </div>
                    ";
            }
        } 
        else{
    ?>
    <div>
        <h1>Register</h1>
        <form id="mainRegister" action="" method="POST" enctype="multipart/form-data">
            <div id="register">
                <div class="pb-upload">
                    <label for="pb">Profile picture:
                        <img src="profilePictures/profilePicture.png"/>
                    </label>

                    <input type="file" name="pb" id="pb"/>
                </div><br><br>

                <label for="name">Username:</label>
                <input type="text" name="name" id="name" class="textfield" required><br><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="textfield" required pattern="^\w+[@]\w+\.\w+$"><br><br>
                
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
</body>
    <?php
        }
    ?>
</body>
</html>