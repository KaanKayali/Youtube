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

$emailer = $_POST["emailer"];   

if(isset($_FILES["video"]) && isset($_FILES["thumb"]) && isset($_POST["title"]) &&  $emailer != null && $emailer != "" && str_contains($emailer, "@") ){
    $allowedVids = [
        "video/mp4" => "mp4",
        "video/ogg" => "ogg"
    ];

    $allowedImages = [
        "image/png" => "png",
        "image/jpeg" => "jpeg",
        "image/jpg" => "jpg",
        "image/gif" => "gif"
    ];

    $title = $_POST["title"];

    $video = prepareFile("video", "videos", $allowedVids);
    $image = prepareFile("thumb", "thumbnails", $allowedImages);
    $description = $_POST["description"];
    $date = date('y-m-d h:i:s');

    if(move_uploaded_file($video[0], $video[1]) && move_uploaded_file($image[0], $image[1])){
        $userQuery = "SELECT * FROM users WHERE email = '$emailer'";
        $userQueryResult = mysqli_query($conn, $userQuery);
        $userid = mysqli_fetch_assoc($userQueryResult);
        $userid = $userid["id"];

        $description = htmlspecialchars($description);
        $title = htmlspecialchars($title);


        if($_POST["description"] == "" || $_POST["description"] == " "){
            $description = "No description set.";
        }
        //INSERT
        $query = "INSERT INTO videos (url, thumbnail, title, description, date, users_id) VALUES ('$video[2]', '$image[2]', '$title', '$description', '$date', '$userid')";
        $query = mysqli_query($conn, $query);
    } else{
        return null;
    }
} else{
    echo "Please log in.";
}

