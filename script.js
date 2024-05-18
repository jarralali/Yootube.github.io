document.getElementById("uploadButton").addEventListener("click", function() {
    window.location.href = "upload.php";
});

document.getElementById('menu-icon').addEventListener('click', function() {
    var sidebar = document.getElementById('sidebar');
    var content = document.querySelector('.content');
    if (sidebar.classList.contains('show-sidebar')) {
        sidebar.classList.remove('show-sidebar');
        content.style.marginLeft = '0';
    } else {
        sidebar.classList.add('show-sidebar');
        content.style.marginLeft = '250px';
    }
});

function openVideoInNewTab(videoUrl) {
    // Open the video in a new tab/window
    window.open(videoUrl, '_blank');
}

function openVideoWithRedirect(videoUrl) {
    // Redirect to video-play.html with the videoUrl as a query parameter
    window.location.href = 'video-play.html?videoUrl=' + encodeURIComponent(videoUrl);
}
