<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerTube</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navStyle.css">
</head>
<body>
    <div id="wrapperer">
        <nav class="navigation" style="background-color: rgb(60, 59, 59);">
            <ul>
                <div class='menu'>
                    <h1 id="logo" onclick="resetPage()">Sneak</h1>
                    <form id="search" action="" enctype="multipart/form-data">
                        <input id="searchbar" name="searchbar" type="text" class="textfield" placeholder="Search..." style="background-color: rgba(255, 255, 255, .7); color:rgb(60, 59, 59)" value="<?php if(isset($_GET["searchbar"])) echo $_GET["searchbar"]?>">
                        <input type="submit" value="Search" name="submit" class="button" style="background-color: rgb(37, 37, 37);">
                    </form>
                    <li><a href="#" class='eat'>Damjan</a></li>
                    
                    <li><a href="videoUpload.php" class="eat">Upload Video</a>
                </div>
            </ul>
            <div class="menu" >
                <?php

                    session_start();

                    require "config.php";
                    if(isset($_SESSION["email"])){
                        $currentMail = $_SESSION["email"];

                        $query = "SELECT * FROM users WHERE email = '$currentMail'";
                        $content = mysqli_fetch_assoc(mysqli_query($conn, $query));

                        echo "<a href='account.php'><img src='Login/profilePictures/". $content["pb"]."' width='42px' height='42px'/></a>";
                    } else{
                        
                
                ?>
                <ul>
                    <li><a href="Login/login.php" class='eat'>Sign in</a></li>
                    <li><a href="Login/register.php" class='eat'>Register</a></li>
                </ul>
                <?php
                    }
                ?>
            </div>
        </nav><br>
        <section id="indexSection">
            <h1 id='videosTitle'>Videos</h1>
            <div class="container">
                <?php
                    require "config.php";
                    require_once "getid3/getid3/getid3.php";
                    $videoIds = array();

                    if (isset($_GET["searchbar"])) {
                        $searchinput = $_GET["searchbar"];
                        $searchinput = htmlspecialchars($searchinput);
                        $query = "SELECT * FROM videos WHERE title like '". "%" ."$searchinput". "%" ."'";
                        $query = mysqli_query($conn, $query);


                        $userQuery = "SELECT * FROM users WHERE username like '". "%" ."$searchinput". "%" ."'";
                        $resultUserQuery = mysqli_query($conn, $userQuery);

                        $userSelectionQueryResults = null;

                        while($user = mysqli_fetch_assoc($resultUserQuery)){
                            $userSelection = "SELECT * FROM videos WHERE users_id = '". $user["id"] ."'";
                            $userSelectionQueryResults = mysqli_query($conn, $userSelection);

    
                            while($data = mysqli_fetch_assoc($userSelectionQueryResults)){
                                $file = 'videos/' . $data["url"];
                                $getid3 = new getID3;

                                array_push($videoIds, $data["id"]);

                                $fileInfo = $getid3->analyze($file);

                                // Get duration of the video
                                $duration = $fileInfo["playtime_seconds"];
                                $duration = getMinutes($duration);

                                echo "
                                    <div>
                                        <div id='accessVideoBtn' data-video-id='" . $data["id"] . "' onclick='addViews(this)'>
                                            <a href='videoPlayer.php?id=" . $data["id"] . "'>
                                                <img src='thumbnails/" . $data["thumbnail"] . "' width='320px' height='180px' id='thumbnail'></img><br>
                                                <h1 id='title'>
                                                    " . $data["title"] ."
                                                </h1>
                                                <h4 id='amtViews' id='title' style='display: inline-block; vertical-align: middle;'>" . number_format($data["views"], 0, '', "'") . " Views</h4>
                                                <div style='display: inline-block; vertical-align: middle; text-align: right; float:right'>
                                                    <h5 id='date' style='float: right;'>" . date_format(date_create($data["date"]), "d.m.y") . "</h5>
                                                </div>
                                                <h4 id='amtViews'>". $duration . "</h4>
                                            </a>
                                        </div>
                                    </div><br>
                                ";
                            }
                        }

                    }
                    else{
                        $query = "SELECT * FROM videos";
                        $query = mysqli_query($conn, $query);
                    }

                    error_reporting(E_ERROR | E_PARSE);

                    if(mysqli_num_rows($query) == 0 && $userSelectionQueryResults === null){
                        echo "<h2 id='novideoserror'>No Videos</h2>";
                    }
                    function getMinutes($seconds){
                        $minutes = floor($seconds / 60);
                        $seconds = $seconds % 60;
                        return sprintf("%02d:%02d", $minutes, $seconds);
                    }


                    while($data = $query->fetch_assoc()){
                        $file = 'videos/' . $data["url"];
                        $getid3 = new getID3;

                        $fileInfo = $getid3->analyze($file);

                        // Get duration of the video
                        $duration = $fileInfo["playtime_seconds"];
                        $duration = getMinutes($duration);

                        if(!in_array($data["id"], $videoIds)){
                            echo "
                            <div>
                                <div id='accessVideoBtn' data-video-id='" . $data["id"] . "' onclick='addViews(this)'>
                                    <a href='videoPlayer.php?id=" . $data["id"] . "'>
                                        <img src='thumbnails/" . $data["thumbnail"] . "' width='320px' height='180px' id='thumbnail'></img><br>
                                        <h1 id='title'>
                                            " . $data["title"] ."
                                        </h1>
                                        <h4 id='amtViews' id='title' style='display: inline-block; vertical-align: middle;'>" . number_format($data["views"], 0, '', "'") . " Views</h4>
                                        <div style='display: inline-block; vertical-align: middle; text-align: right; float:right'>
                                            <h5 id='date' style='float: right;'>" . date_format(date_create($data["date"]), "d.m.y") . "</h5>
                                        </div>
                                        <h4 id='amtViews'>". $duration . "</h4>
                                    </a>
                                </div>
                            </div><br>
                        ";
                        }
                    }
                ?>
            </div>
        </section>
    </div>
</body>
</html>
