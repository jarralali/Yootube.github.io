<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "videos";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_create_db) === FALSE) {
    echo "Error creating database: " . $conn->error;
}

$conn->select_db($dbname);

$sql_create_table = "CREATE TABLE IF NOT EXISTS videos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_path VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_create_table) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video_path = "uploads/" . basename($_FILES["video"]["name"]);


    if (move_uploaded_file($_FILES["video"]["tmp_name"], $video_path)) {
        $sql_insert_video = "INSERT INTO videos (title, description, video_path) VALUES ('$title', '$description', '$video_path')";

        if ($conn->query($sql_insert_video) === TRUE) {
            echo "Video uploaded successfully";
        } else {
            echo "Error: " . $sql_insert_video . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video</title>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <div class="upload-container">
                <h2>Upload Video</h2>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        placeholder="Description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="video">Choose Video</label>
                    <input type="file" class="form-control-file" id="video" name="video" accept="video/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>

</body>

</html>