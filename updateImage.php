<?php
    require "config.php";

    function prepareFile($name, $dir, $allowed){
        require "config.php";
    
        $filepath = $_FILES[$name]["tmp_name"];
        $filesize = $_FILES[$name]["size"];
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath); 
        finfo_close($fileinfo);
    
        if(!in_array($filetype, array_keys($allowed))){
            die("File not Allowed");
        }
    
        if($filesize > (500 * 1024 * 1024)){ //500 mb
            die("File Size too large");
        }
    
        $filename = $_FILES[$name]["name"];
        $targetDir = __DIR__ . "/" . $dir;
    
        $newPath = $targetDir . "/" . $filename;
    
        return [$filepath, $newPath, $filename];
    }

    $allowedImages = [
        "image/png" => "png",
        "image/jpeg" => "jpeg",
        "image/jpg" => "jpg"
    ];

    $newusername = $_POST["username"];
    $mail = $_POST["email"];

    if(isset($_FILES['pb']) && $_FILES['pb']['error'] !== 4){
        $image = prepareFile("pb", "Login/profilePictures", $allowedImages);

        if(move_uploaded_file($image[0], $image[1])){
            $pb = $_FILES["pb"]["name"];
            
            move_uploaded_file($_FILES["pb"]["tmp_name"], "Login/profilePictures/" . $pb);
            
            $query = "UPDATE users SET pb = '$pb' WHERE email = '$mail'";
            $result = mysqli_query($conn, $query);
        }
        echo $mail;
    }

    $query2 = "UPDATE users SET username = '$newusername' WHERE email = '$mail'";
    mysqli_query($conn, $query2);

?>