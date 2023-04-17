<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerTube</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navStyle.css">
    <script src="script.js" defer></script>
</head>
<body id="videoPlayerBody">
    <div id="alles">
        <div id="nowrapper">
            <?php
                session_start();

                require "config.php";
                require_once "getid3/getid3/getid3.php";

                if(isset($_GET['data'])) {
                   $id = $_GET['data'];
                }
                $query = "SELECT * FROM users WHERE id = '$id'";
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0){
                    $data = mysqli_fetch_assoc($result);

                    $userQuery = "SELECT * FROM users WHERE id = '". $data["id"] ."'";
                    $userContent = mysqli_fetch_assoc(mysqli_query($conn, $userQuery));

                    
            ?>
            <h1 style="color:white"><?php $userContent["username"]?></h1>
            <section id="accountsection">
                <div class="pb-upload" style="display:block;">
                    <label for="pb" id="pbLbl">
                        <?php
                            echo "<img src='Login/profilePictures/". $userContent["pb"]."' width='100px' height='100px' id='imgpb'/>";
                        ?>
                    </label>
                    
                </div>
                <?php

                $allviews = 0;
                $queryvideo = "SELECT * FROM videos WHERE users_id = '$id'";

                $contentvideo = mysqli_query($conn, $queryvideo);
                $contentvideo2 = mysqli_query($conn, $queryvideo);
                function getMinutes($seconds){
                    $minutes = floor($seconds / 60);
                    $seconds = $seconds % 60;
                    return sprintf("%02d:%02d", $minutes, $seconds);
                }

                while ($row = mysqli_fetch_assoc($contentvideo2)){
                    $allviews = $allviews + $row["views"];
                }
                $createddate = $userContent["createddate"];
    
                echo"<h3>" . $userContent['username'] ."</h3>";                 
                echo"<h4>" . $userContent['subscribers'] ." Subscribers</h4><br>";
                echo"<h4>" . $allviews ." Views</h4>";
                echo"<h5>Created on " . date_format(date_create($createddate), "d.m.y") ." </h5>";

                ?>
                
            </section><br>
                <a href="index.php">
                    <button class="button">Back</button>
                </a><br><br>
                <h2 id='videosTitle'>Videos</h2>
                <div class="container">
                    <?php 
                        while($data = mysqli_fetch_assoc($contentvideo)){
                            $file = 'videos/' . $data["url"];
                            $getid3 = new getID3;

                            $fileInfo = $getid3->analyze($file);

                            // Get duration of the video
                            $duration = $fileInfo["playtime_seconds"];
                            $duration = getMinutes($duration);

                            echo "
                            <div id=''>
                                <div id='accessVideoBtn' data-video-id='" . $data["id"] . "' onclick='addViews(this)'>
                                    <a href='videoPlayer.php?id=" . $data["id"] . "' style='height: 10px'>
                                        <img src='thumbnails/" . $data["thumbnail"] . "' width='320px' height='180px' id='thumbnail'></img><br>
                                        <h1 id='title'>
                                            " . $data["title"] ."
                                        </h1>
                                        <h4 id='amtViews' id='title' style='display: inline-block; vertical-align: middle;'>" . $data["views"] . " Views</h4>
                                        <div style='display: inline-block; vertical-align: middle; text-align: right; float:right'>
                                            <h5 id='date' style='float: right;'>" . date_format(date_create($data["date"]), "d.m.y") . "</h5>
                                        </div>
                                        <h4 id='amtViews'>". $duration . "</h4>
                                    </a>    
                                </div>
                            </div><br>
                        ";

                        }
                    ?>
                </div>
        </div>
        <?php
            }
        ?>
    </div>
    <script defer>
        document.getElementById("fileForm").addEventListener("submit", e=> {
            e.preventDefault();

            fetch("updateImage.php",{
                method: 'POST',
                body: new FormData(e.target)
            }).then(response => response.text())
            .then(data => {
                console.log(data);
                window.location.replace("index.php");
            })
        })

        function logout(){
            const LOGINTOKEN = "logintoken";
            localStorage.removeItem(LOGINTOKEN);

            fetch("logout.php").then(response => response.text())
            .then(data =>{
                console.log(data);
            });
        }
    </script>
</body>
</html>