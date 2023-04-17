document.getElementById("former").addEventListener("submit", function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const url = form.getAttribute("action") + "?id=" + form.getAttribute("data-video-id");
  
    if(localStorage.getItem("logintoken") && localStorage.getItem("logintoken").includes("@")){
      fetch(url, {
          method: "POST",
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          console.log(data);
          if(data === "Please log in."){
            alert(data);
          }
        })
        .catch(error => {
          console.error(error);
        });
    } else{
      alert("Please log in.")
    }
    setTimeout(function(){
      location.reload();
  }, 100)
});
  