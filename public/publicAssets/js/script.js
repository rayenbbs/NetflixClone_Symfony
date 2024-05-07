//Using JQuery to handle the navigation bar's style (changing the background color on scroll)
$(document).scroll(function() {
    var isScrolled = $(this).scrollTop() > $(".topBar").height();
    $(".topBar").toggleClass("scrolled", isScrolled);
})

//using JQuery to handle the muting and unmuting of the video by changing the html tag properties and displaying adequate icons

function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);
    $(button).find("i").toggleClass("fa-volume-xmark");
    $(button).find("i").toggleClass("fa-volume-high");

}


//using JQuery to show the image after the video preview is over
function previewEnded() {
    $(".previewVideo").toggle();
    $(".previewImage").toggle();
}

//Go back button funtionality on the watch video page
function goBack() {
    window.history.back();
}

//Fade in and out of the video navigation bar on the watch page
function startHideTimer(){
    var timeout = null;
    $(document).on("mousemove", function(){
        clearTimeout(timeout);
        $(".watchNav").fadeIn();

        timeout = setTimeout(function() {
            $(".watchNav").fadeOut();
        }, 2000);
    })
}

function initVideo(videoId, username) {
    startHideTimer();
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);
}

//Updating video progress every 3 secs, marking video as finished. Adding progress if it doesn't exist.
function updateProgressTimer(videoId, username) {
    addDuration(videoId, username);

    var timer;

    $("video").on("playing", function(event) {
        window.clearInterval(timer);
        timer = window.setInterval(function() {
            updateProgress(videoId, username, event.target.currentTime);
        }, 3000);
    })
        .on("ended", function() {
            setFinished(videoId, username);
            window.clearInterval(timer);
        })
}

function addDuration(videoId, username) {
    $.post("ajax/addDuration.php", { videoId: videoId, username: username }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })
}

function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", { videoId: videoId, username: username, progress: progress }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })
}

function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", { videoId: videoId, username: username }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })
}

//Starting video where it was left off.
function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", { videoId: videoId, username: username }, function(data) {
        if(isNaN(data)) {
            alert(data);
            return;
        }

        $("video").on("canplay", function() {
            this.currentTime = data;
            $("video").off("canplay");
        })
    })
}
//Restarting the video after it is finished
function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
}
//watching the video by redirecting to the watch.php page
function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
}
//fade in for the up next video after the current video is finished
function showUpNext() {
    $(".upNext").fadeIn();
}


