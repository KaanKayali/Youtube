<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploading video</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body id="videoUploadBody">
    <div id="page">
        <form id="main" action="upload.php" enctype="multipart/form-data">
            <div class="video-upload">
                <label for="video">Video:<br>
                    <img src="images/videoplaceholder.jpeg" width='160px' height='90px' id="videoplaceholder"/>
                </label>
                <input type="file" id="video" name="video" required><br>
                
                    
            </div><br>
            <div class="thumb-upload">
                <label for="thumb">Thumbnail:<br>
                    <img src="images/thumbnailplaceholder.jpeg" width='160px' height='90px' id="thumbplaceholder"/>
                </label>
                <input type="file" id="thumb" name="thumb" required onchange="replaceForVids()"><br>
                    
            </div><br>

            <label for="title">Title:</label>
            <input type="text" id="titleLbl" name="title" class="textfield" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea><br>

            <input type="submit" value="Upload" name="submit" id="submit" class="button">
            <?php
                require 'config.php';
                session_start();
                if(isset($_SESSION["email"])){
                    echo "
                    <input type='text' style='display: none' name='emailer' value='". $_SESSION["email"] ."'>
                    ";
                }
            ?>
        </form><br>
        <a href="index.php">
            <button class="button">Back</button>
        </a>
    </div>
    <div id="messageDiv">
        <h1 id="loginMessage"></h1><br>
        <h1 style="display:none" id="redirectLbl">Redirecting...</h1>
    </div>
</body>
</html>