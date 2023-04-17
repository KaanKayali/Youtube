<?php

require "config.php";
$method = $_GET["method"];
$table = "";

if(isset($_GET["table"])){
    $table = $_GET["table"];
}

$id = $_GET["id"];
$userid = $_GET["userid"];
$uploaderid = "";
if(isset($_GET["uploaderid"])){
    $uploaderid = $_GET["uploaderid"];
}




if($method == "subscribers"){
    if($userid == $uploaderid){
        die("You can't subscribe to yourself");
    }

    $checkQuery = "SELECT * FROM $method WHERE user_id = '$userid' AND creator_id = '$uploaderid'";
    $result = mysqli_query($conn, $checkQuery);

    if(mysqli_num_rows($result) > 0){
        $query = "UPDATE $table SET $method = $method - 1 WHERE id = '$uploaderid'";
        mysqli_query($conn, $query);

        $query2 = "DELETE FROM $method WHERE user_id = '$userid' AND creator_id = '$uploaderid'";
        mysqli_query($conn, $query2);
    } else{
        $query = "UPDATE $table SET $method = $method + 1 WHERE id = '$uploaderid'";
        mysqli_query($conn, $query);
    
        $query2 = "INSERT INTO $method VALUES ('$userid', '$uploaderid')";
        mysqli_query($conn, $query2);
        
        echo $id;  
    }

} else if($method == "views"){
    $query = "UPDATE videos SET $method = $method + 1 WHERE id = '$id'";
    mysqli_query($conn, $query);
} 
else{
    $checkQuery = "SELECT * FROM $method WHERE users_id = '$userid' AND video_id = '$id'";
    $result = mysqli_query($conn, $checkQuery);
    
    if(mysqli_num_rows($result) > 0){
        $query = "UPDATE $table SET $method = $method - 1 WHERE id = '$id'";
        mysqli_query($conn, $query);

        $query2 = "DELETE FROM $method WHERE users_id = '$userid' AND video_id = '$id'";
        mysqli_query($conn, $query2);
    } else{
        $query = "UPDATE $table SET $method = $method + 1 WHERE id = '$id'";
        mysqli_query($conn, $query);
    
        $query2 = "INSERT INTO $method VALUES ('$userid', '$id')";
        mysqli_query($conn, $query2);
        
        echo $id;
    }
}



