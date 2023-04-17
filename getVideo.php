<?php

require 'config.php';

$id = $_GET["id"];

$query = "SELECT * FROM videos WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $video = mysqli_fetch_assoc($result);
    $videoUrl = $video["url"];

    echo $videoUrl;
}

mysqli_close($conn);