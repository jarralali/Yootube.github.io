<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "videos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_videos = "CREATE TABLE IF NOT EXISTS videos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_path VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_videos) === FALSE) {
    echo "Error creating videos table: " . $conn->error;
}

$sql_comments = "CREATE TABLE IF NOT EXISTS comments (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    video_id INT(6) NOT NULL,
    comment TEXT,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_comments) === FALSE) {
    echo "Error creating comments table: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"])) {
    $comment = $_POST["comment"];
    $video_id = $_POST["video_id"];

    $sql_insert_comment = "INSERT INTO comments (video_id, comment) VALUES ('$video_id', '$comment')";

    if ($conn->query($sql_insert_comment) === TRUE) {

    } else {
        echo "Error: " . $sql_insert_comment . "<br>" . $conn->error;
    }
}

$sql_fetch_videos = "SELECT * FROM videos";
$result_videos = $conn->query($sql_fetch_videos);

$videos = [];
if ($result_videos !== FALSE && $result_videos->num_rows > 0) {
    while ($row = $result_videos->fetch_assoc()) {
        $videos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            right: 20px;
            justify-content: center;
            height: 80%;
        }

        .video-play {
            position: relative;
            right: 100px;
        }

        .video-list {
            width: 30%;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            position: absolute;
            right: 20px;
            background-color: white;
            z-index: 1;
        }

        .video-list .side-video-list {
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        .side-video-list img {
            width: 120px;
            height: auto;
            margin-right: 10px;
        }

        .vid-info {
            flex-grow: 1;
        }

        .vid-info a {
            color: #333;
            font-weight: bold;
            text-decoration: none;
        }

        .vid-info p {
            margin: 5px 0;
            color: #666;
        }

        .comments-container {
            width: 60%;

            justify-content: center;
            margin-top: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            margin-left: 200px;

        }

        .comments-container p {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .comment {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
        }

        .comment p {
            margin: 5px 0;
            color: #555;
        }

        .comment-container {
            margin-top: 20px;
        }

        .comment-container textarea {
            width: calc(100% - 20px);
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            resize: vertical;
        }

        .comment-container button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .video-controls button {
            justify-content: center;
            align-items: center;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .video-title {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .video-date {
            margin-top: 5px;
            color: #666;
        }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="flex-div">
        <div class="nav-left flex-div">
            <img src="images/menu.png" class="menu-icon">
            <a href="index.html">
                <img src="images/logo.png" class="logo">
            </a>
        </div>
        <div class="nav-middle flex-div">
            <div class="search-box flex-div">
                <input type="text" placeholder="search">
                <img src="images/search.png">
            </div>
            <img src="images/voice-search.png" class="mic-icon">
        </div>
        <div class="nav-right flex-div">
            <img src="images/upload.png">
            <img src="images/more.png">
            <img src="images/notification.png">
            <a href="upload.php">
                <img src="images/Jack.png" class="user-icon">
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="video-play">
            <video id="videoPlayer" controls autoplay>
                <source src="" type="video/mp4">
            </video>
            <div class="video-controls">
                <button id="playPauseBtn" onclick="togglePlayPause()">Play</button>
                <button onclick="previousVideo()">Previous</button>
                <button onclick="nextVideo()">Next</button>
            </div>
            <div class="video-title" id="videoTitle"></div>
            <div class="video-date" id="videoDate"></div>
        </div>

        <div class="video-list">
            <?php foreach ($videos as $index => $video): ?>
                <div class="side-video-list">
                    <a href="#" class="small-thumbnail"
                        onclick="loadVideo('<?php echo $video['video_path']; ?>', <?php echo $video['id']; ?>)">
                        <img src="images/thumbnail1.png">
                    </a>
                    <div class="vid-info">
                        <a href="#" onclick="loadVideo('<?php echo $video['video_path']; ?>', <?php echo $video['id']; ?>)">
                            <?php echo $video['title']; ?>
                        </a>
                        <p><?php echo $video['description']; ?></p>
                        <p><?php echo $video['upload_date']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="comments-container">
        <div class="comments" id="comments">
        </div>

        <div class="comment-container">
            <form id="commentForm" action="" method="post">
                <textarea id="commentText" name="comment" rows="4" placeholder="Write your comment here"></textarea><br>
                <input type="hidden" id="videoId" name="video_id" value="">
                <button type="submit">Comment</button>
            </form>
        </div>
    </div>
</body>
<script>
    var videos = <?php echo json_encode($videos); ?>;
    var currentVideoIndex = 0;

    loadVideo(videos[0]['video_path'], videos[0]['id']);

    function loadComments(videoId) {
        var commentsContainer = document.getElementById('comments');
        commentsContainer.innerHTML = '';

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var comments = JSON.parse(this.responseText);
                if (comments.length > 0) {
                    commentsContainer.innerHTML += "<p>Comments for Selected Video:</p>";
                    comments.forEach(function (comment) {
                        commentsContainer.innerHTML += "<div class='comment'><p>" + comment.comment + "</p></div>";
                    });
                } else {
                    commentsContainer.innerHTML += "<p>No comments for Selected Video.</p>";
                }


                document.getElementById('videoId').value = videoId;
            }
        };
        xhr.open("GET", "fetch_comments.php?video_id=" + videoId, true);
        xhr.send();
    }


    function loadVideo(videoPath, videoId) {
        var videoPlayer = document.getElementById('videoPlayer');
        var videoTitle = document.getElementById('videoTitle');
        var videoDate = document.getElementById('videoDate');
        var playPauseBtn = document.getElementById('playPauseBtn');

        videoPlayer.src = videoPath;
        videoPlayer.play();
        videoTitle.textContent = videos.find(video => video.id === videoId).title;
        videoDate.textContent = videos.find(video => video.id === videoId).upload_date;
        playPauseBtn.textContent = 'Pause';

        loadComments(videoId);

        var newUrl = window.location.href.split('?')[0] + '?video_id=' + videoId;
        window.history.pushState("", "", newUrl);
    }

    function togglePlayPause() {
        var video = document.getElementById('videoPlayer');
        var playPauseBtn = document.getElementById('playPauseBtn');

        if (video.paused) {
            video.play();
            playPauseBtn.textContent = 'Pause';
        } else {
            video.pause();
            playPauseBtn.textContent = 'Play';
        }
    }

    function nextVideo() {
        currentVideoIndex = (currentVideoIndex + 1) % videos.length;
        loadVideo(videos[currentVideoIndex]['video_path'], videos[currentVideoIndex]['id']);
    }

    function previousVideo() {
        currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
        loadVideo(videos[currentVideoIndex]['video_path'], videos[currentVideoIndex]['id']);
    }
</script>

</html>