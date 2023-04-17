<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerTube</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body id="videoPlayerBody">
    <div id="alles">
        <div id="wrapper">
            <?php
                session_start();
                require "config.php";
                require_once "getid3/getid3/getid3.php";

                $id = $_GET["id"];
                $emailAuth = "";
                    
                if(isset($_SESSION["email"])){
                    $emailAuth = $_SESSION["email"];

                } else{
                    $emailAuth = "Warning! You're not logged in.";
                }

                $currentUserQuery = "SELECT * FROM users WHERE email = '$emailAuth'";
                if(isset($_SESSION["email"])){
                    $currentUser = mysqli_fetch_assoc(mysqli_query($conn, $currentUserQuery));
                }
                

                $query = "SELECT * FROM videos WHERE id = '$id'";
                $result = mysqli_query($conn, $query);
                if(mysqli_num_rows($result) > 0){
                    $data = mysqli_fetch_assoc($result);

                    $userQuery = "SELECT * FROM users WHERE id = '". $data["users_id"] ."'";
                    $userContent = mysqli_fetch_assoc(mysqli_query($conn, $userQuery));
                    $datapassing = $userContent["id"];
                    
                    
                    echo "
                            <div data-video-id='". $data["users_id"] ."' data-user-id='". $currentUser["id"] ."' id='getIdDiv''>
                                <h1 id='title' style='display: inline-block; vertical-align: middle;'>
                                " . $data["title"] ."
                                </h1><br>
                                <div id='rectanglebehindvideo'></div>
                                <div id='thevideodiv'><video id='thevideo' src='videos/" . $data["url"] . "' height='562px' controls></video></div><br>
                                <div id='profileDiv' style='display: inline-block; vertical-align: middle; text-align: left; float:left' id='likeDiv'>
                                    <a href='accountother.php?data=" . urlencode($datapassing) . "'><img src='Login/profilePictures/". $userContent["pb"]."' width='50px' height='50px'/></a>
                                    <h4 style='color:#cfcfcf'>". $userContent["username"] ." </h4>
                                    <em id='amtSubscribers'>". number_format($userContent["subscribers"], 0, '', "'") ." Subscribers</em> <br>
                                    <button class='button' id='subscribe' onclick='executeSubscribe(this)' data-users-id='" . (isset($_SESSION["email"]) ? $currentUser["id"] : "") . "' data-uploader-id='" . $data["users_id"] . "'>Subscribe</button>
                                </div><br><br><br>
                                <div style='display: inline-block; vertical-align: middle; text-align: right; float:right' id='likeDiv'>
                                    <h4 id='amtViews'>" . number_format($data["views"], 0, '', "'") . " Views</h4>
                                    <button class='button' id='like' onclick='executeLike(this)' data-video-id='" . $id . "' data-users-id='" . (isset($_SESSION["email"]) ? $currentUser["id"] : "") . "'>Like</button> <em id='amtLikes'>". number_format($data["likes"], 0, '', "'") ."</em>
                                    <button class='button' id='dislike' data-video-id='" . $id . "' onclick='executeDislike(this)' data-users-id='" . (isset($_SESSION["email"]) ? $currentUser["id"] : "") . "'>Dislike</button> <em id='amtDislikes'>". number_format($data["dislikes"], 0, '', "'") ."</em>
                                </div><br><br><br><br>
                                <h5 id='date'>". date_format(date_create($data["date"]), "d.m.y") ."</h5>
                                <h4 id='desc'>". $data["description"] ."</h4>
                                <a href='index.php'>
                                    <button class='button'>Back</button>    
                                </a>
                            </div><br><br><br>";

                    if(isset($_SESSION["email"])){
                        $currentMail = $_SESSION["email"];

                        $queryuser = "SELECT * FROM users WHERE email = '$currentMail'";
                        $contentuser = mysqli_fetch_assoc(mysqli_query($conn, $queryuser));
                    
                        echo"
                            <section>
                                <div>
                                    <form id='former' action='addComment.php' data-video-id='". $id ."' method='POST'>
                                        <p id='commentLbl'>Comment:</p>
                                        <textarea name='comment' id='comment' rows='2' placeholder='Type in your comment'></textarea><br>
                                        <input type='submit' value='Upload comment' name='submitComment' id='submitComment' class='button'>
                                        <input type='text' style='display:none' name='authMail' value='". $emailAuth ."'/>
                                    </form>
                                </div>
                            </section><br>
                            <script defer src='playerScript.js'></script>";
                    }
                    else{
                        echo "<h4 style='color:#cfcfcf'>Log in to write a comment</h4>";
                    }

                    require 'config.php';
                    $id = $_GET["id"];

                    $sql = "SELECT * FROM comments WHERE video_id = '" . $id ."'";
                    
                    
                    $comments = mysqli_query($conn, $sql);
                    $amtComments = mysqli_num_rows($comments);

                    if($amtComments > 1 || $amtComments == 0){
                        echo "<h1 id='amtComments'>". $amtComments ." Comments</h1>";
                    } else if ($amtComments == 1){
                        echo "<h1 id='amtComments'>". $amtComments ." Comment</h1>";
                    }

                    if (mysqli_num_rows($comments) > 0) {
                        while($row = mysqli_fetch_assoc($comments)) {
                            $query = "SELECT * FROM users WHERE id = '". $row["users_id"] ."'";
                            $content = mysqli_fetch_assoc(mysqli_query($conn, $query));

                            $videoInf = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM videos WHERE id = '$id'"));

                            if($videoInf["users_id"] == $row["users_id"]){
                                if($content != null){
                                    echo "
                                        <div class='comment-section'>
                                            <img src='Login/profilePictures/". $content["pb"]."' width='42px' height='42px'/>
                                            <div class='comment' style='color: gray;display: inline-block; vertical-align: middle; color: green'>". $content["username"] ." (Creator)</div>
                                            <div class='comment'>" . $row["comment"] ."</div>
                                            <div class='date' style='display: inline-block; vertical-align: middle;'>". date_format(date_create($row["date_created"]), "d.m.y") ."</div>
                                        </div>
                                    ";
                                }
                            } else{
                                if($content != null){
                                    echo "
                                        <div class='comment-section'>
                                            <img src='Login/profilePictures/". $content["pb"]."' width='42px' height='42px'/>
                                            <div class='comment' style='color: gray;display: inline-block; vertical-align: middle;'>". $content["username"] ."</div>
                                            <div class='comment'>" . $row["comment"] ."</div>
                                            <div class='date' style='display: inline-block; vertical-align: middle;'>". date_format(date_create($row["date_created"]), "d.m.y") ."</div>
                                        </div>
                                    ";
                                }
                            }

                            
                        }
                    } else {
                        echo "<div class='comment'>No Comments.</div>";
                    }
                }
            ?>
            
        </div>
        <div id="videosDiv">
            <?php   
                require "config.php";
                require_once "getid3/getid3/getid3.php";

                $query = "SELECT * FROM videos LIMIT 50";
                $query = mysqli_query($conn, $query);

                if(mysqli_num_rows($query) == 0){
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

                    $thisVid = $_GET["id"];

                    if($thisVid != $data["id"]){
                        echo "
                            <div>
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
                }
            ?>
        </div>
    </div>
    <script defer>
        function checkAccount(elem){

            const userid = elem.getAttribute("data-user-id");
            const videoid = elem.getAttribute("data-video-id");
            if(userid == videoid){
                let subbtn = document.getElementById("subscribe");
                subbtn.disabled = true;
            }
            console.log(userid);
            console.log(videoid);
        }

        checkAccount(document.getElementById("getIdDiv"));
    </script>
</body>
</html>