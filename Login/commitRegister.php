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
    $createddate = date("Y-m-d");
    
    $allowedImages = [
        "image/png" => "png",
        "image/jpeg" => "jpeg",
        "image/jpg" => "jpg",
        "image/gif" => "gif"
    ];
    $filename = "";
    $pb = "";
    if(!isset($_FILES['pb']) || $_FILES['pb']['error'] === 4){
        $filename = "profilePicture.png";
    } else{
        $pb = prepareFile("pb", "profilePictures", $allowedImages);
        $filename = $pb[2];
        move_uploaded_file($pb[0], $pb[1]);
    }
    $checkDuplicate = "SELECT * FROM users WHERE email='$email'";
    $checkDuplicate = mysqli_query($conn, $checkDuplicate);
    $rows = mysqli_num_rows($checkDuplicate);
        

    if($rows == 0){
        $query = "INSERT INTO users (pb, username, email, password, createddate)
              VALUES ('$filename', '$name', '$email', '$hash', '$createddate')";
        $query = mysqli_query($conn, $query);
        if($query){
            $_SESSION['email'] = $email;
            echo $email;
        }
    } else{
        echo "Email already exists in our database";
    }
} 