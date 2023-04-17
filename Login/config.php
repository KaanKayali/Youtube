<?php
$conn = mysqli_connect("localhost", "root", "", "youtube");

if(mysqli_connect_errno()){
    echo "An error occured " . mysqli_connect_errno();
}
?>