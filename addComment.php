<?php
require "config.php";

$video_id = $_GET["id"];
$comment = $_POST["comment"];
$email = $_POST["authMail"];
if($email != null && $email != "" && $email != " " && $email){
    $selectQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $selectQuery);

    $comment = htmlspecialchars($comment);
    
    $data = mysqli_fetch_assoc($result);
    $id = $data["id"];
    
    $query = "INSERT INTO comments (video_id, comment, date_created, users_id) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $video_id, $comment, date('y-m-d h:i:s'), $id);
    
    $stmt->execute();
    $stmt->close();
} else{
    echo "Please log in.";
}

