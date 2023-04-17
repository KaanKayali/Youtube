const LOGINTOKEN = "logintoken";

const session = localStorage.getItem(LOGINTOKEN);

document.getElementById("main").addEventListener("submit", function(e){
    e.preventDefault();
    if(session && session.includes("@")){
        fetch("upload.php", {
            method: "POST",
            body: new FormData(e.target)
        }).then(result => result.text()).then(data => {
            document.getElementById("videoUploadBody").style.justifyContent = "center";
            document.getElementById("videoUploadBody").style.alignItems = "center";
            if(data !== "Please log in."){
                document.getElementById("page").innerHTML = "";
                document.getElementById("loginMessage").innerText = "Successfully uploaded video.";
                document.getElementById("redirectLbl").style.display = "";
                setTimeout(function(){
                    window.location.replace("index.php");
                }, 2000)
            } else{
                document.getElementById("page").innerHTML = "";
                document.getElementById("loginMessage").innerText = "Something went wrong";
                document.getElementById("redirectLbl").innerText = "Reloading...";
                document.getElementById("redirectLbl").style.display = "";
                setTimeout(function(){
                    window.location.reload();
                }, 2000)
            }
        });
    } else{
        document.getElementById("videoUploadBody").style.justifyContent = "center";
        document.getElementById("videoUploadBody").style.alignItems = "center";
        document.getElementById("page").innerHTML = "";
        document.getElementById("loginMessage").innerText = "Please log in.";
        document.getElementById("redirectLbl").innerText = "Reloading...";
        document.getElementById("redirectLbl").style.display = "";
        setTimeout(function(){
            window.location.reload();
        }, 2000)
    }
    
});


function resetPage(){
    window.location.replace("index.php");
}


//The following code is not in use
function accessVideo(elem){
    const videoID = elem.getAttribute("data-video-id");
    fetch('getVideo.php?id=' + videoID)
    .then(response => response.text())
    .then(url => {
        const video = document.createElement("video");
        video.src = "videos/" + url;
        video.controls = true;
        video.width = 600;

        const thumbnail = elem.querySelector("img");
        thumbnail.replaceWith(video);
    })
    .catch(error => console.log(error));
}

function closeVideo(elem){
    const videoID = elem.getAttribute("data-video-id");
    fetch('getThumbnail.php?id=' + videoID)
    .then(response => response.text())
    .then(url => {
        console.log(url);
        const thumb = document.createElement("img");
        thumb.src = "thumbnails/" + url;
        thumb.width = 600;

        const video = elem.querySelector("video");
        video.replaceWith(thumb);
    })
    .catch(error => console.log(error));
}

function executeLike(elem){
    const id = elem.getAttribute("data-video-id");
    const userid = elem.getAttribute("data-users-id");
    console.log(session);
    if(session && session.includes("@")){
        fetch('addLikes.php?method=likes&table=videos&userid=' + userid + '&id=' + id).then(response => response.text())
        .then(data => {
            console.log(data);
        })
        setTimeout(function(){
            location.reload();
        }, 100)
    } else{
        alert("Please log in");
    }
}
    
function executeDislike(elem){
    const id = elem.getAttribute("data-video-id");
    const userid = elem.getAttribute("data-users-id");
    if(session && session.includes("@")){
        fetch('addLikes.php?method=dislikes&table=videos&userid=' + userid + '&id=' + id).then(response => response.text())
        .then(data => {
            console.log(data);
        })
        setTimeout(function(){
            location.reload();
        }, 100)
    } else{
        alert("Please log in");
    }
}

function executeSubscribe(elem){
    const uploaderid = elem.getAttribute("data-uploader-id");
    const userid = elem.getAttribute("data-users-id");
    if(session && session.includes("@")){
        fetch('addLikes.php?method=subscribers&table=users&userid='+ userid + '&uploaderid=' + uploaderid).then(response => response.text())
        .then(data => {
            console.log(data);
        })
        setTimeout(function(){
            location.reload();
        }, 100)
    } else{
        alert("Please log in");
    }
}

function addViews(elem){
    const id = elem.getAttribute("data-video-id");
    fetch('addLikes.php?method=views&id=' + id).then(response => response.text())
    .then(data => {
        console.log(data);
    })
}

function replaceForVids(){
    const file = document.getElementById("thumb").files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        document.getElementById("thumbplaceholder").src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function replaceImage() {
    const file = document.getElementById("pb").files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        document.getElementById("imgpb").src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
